<?php namespace AzureDns;

use AzureDns\Exceptions\IncompleteConfigurationException;
use TheNetworg\OAuth2\Client\Provider\Azure;
use Aura\Session\Segment;

/**
 * Class DNSApi
 * @package AzureDns
 * @todo This should cache GET responses, then invalidate them based upon PUT/PATCH actions (cache invalidation is hard)
 */
class DNSApi
{
    /**
     * @var Segment
     */
    private $session;

    /**
     * @var Azure
     */
    private $azure;

    /**
     * @var string
     */
    private $token;

    /** @var null|array */
    private $configuration;

    public function __construct(Segment $session, Azure $azure, $token)
    {
        $this->session = $session;
        $this->azure = $azure;
        $this->token = $token;

        if (!$this->configuration = $session->get('configuration')) {
            $this->configuration = [
                'subscription' => null,
                'group' => null,
            ];
            $session->set('configuration', $this->configuration);
        }
    }

    public function getConfiguration()
    {
        return $this->configuration;
    }

    public function configurationIsValid()
    {
        if (is_null($this->configuration['subscription']) || is_null($this->configuration['group'])) {
            return false;
        }

        return true;
    }

    public function getConfig($key)
    {
        if (!in_array($key, ['subscription', 'group'])) {
            throw new \Exception('[' . $key . '] is not a valid configuration key');
        }

        return $this->configuration[$key];
    }

    public function setConfig($key, $value)
    {
        if (!in_array($key, ['subscription', 'group'])) {
            throw new \Exception('[' . $key . '] is not a valid configuration key');
        }

        $this->configuration[$key] = $value;
        $this->session->set('configuration', $this->configuration);
    }

    /**
     * Get the list of subscriptions the user is allowed to see
     *
     * @link https://msdn.microsoft.com/en-us/library/azure/dn776325.aspx#ListSubscriptions
     * @return array
     */
    public function getSubscriptionsList()
    {
        $subscriptions = [];
        $data = $this->getFromAPI('subscriptions');

        foreach ($data as $subscription) {
            if ($subscription['state'] !== 'Enabled') {
                continue;
            }
            $subscriptions[$subscription['subscriptionId']] = $subscription['displayName'];
        }
        return $subscriptions;
    }

    /**
     * Get the list of groups that belong to the configured subscription
     *
     * @link https://msdn.microsoft.com/en-us/library/azure/dn790529.aspx
     * @return array
     * @throws \Exception
     */
    public function getGroupsList()
    {
        $groups = [];
        $data = $this->getFromAPI('subscriptions/{subscription}/resourceGroups', [], '2015-11-01');

        foreach ($data as $group) {
            $groups[$group['name']] = $group['name'];
        }

        return $groups;
    }

    /**
     * Get a list of DNS zones that are attached to the configured resource group
     *
     * @link https://msdn.microsoft.com/en-us/library/azure/mt130594.aspx
     * @return array
     * @throws \Exception
     */
    public function getZonesList()
    {
        return $this->getFromAPI('subscriptions/{subscription}/resourceGroups/{group}/providers/Microsoft.Network/dnsZones',
            [], '2015-05-04-preview');
    }

    /**
     * Create a new zone
     *
     * @link https://msdn.microsoft.com/en-us/library/azure/mt130622.aspx
     * @param string $zone
     * @return null
     */
    public function createZone($zone)
    {
        return $this->putToAPI(
            'subscriptions/{subscription}/resourceGroups/{group}/providers/Microsoft.Network/dnsZones/{zone}',
            [
                'zone' => $zone
            ],
            [
                'location' => 'global',
                'tags' => new \stdClass(),
                'properties' => new \stdClass()
            ],
            '2015-05-04-preview'
        );
    }

    /**
     * Delete a given zone, if successful the azure api will respond with status code 200.
     *
     * @link https://msdn.microsoft.com/en-us/library/azure/mt130587.aspx
     * @param $zone
     * @return bool
     * @throws \Exception
     */
    public function deleteZone($zone)
    {
        $this->azure->API_VERSION = '2015-05-04-preview';

        $path = $this->buildAPIPath(
            'subscriptions/{subscription}/resourceGroups/{group}/providers/Microsoft.Network/dnsZones/{zone}',
            ['zone' => $zone]
        );
        $data = $this->azure->delete($path, $this->token);
        $this->throwOnAPIError($data);

        // We would have an exception thrown before here if there was an error deleting the zone
        return true;
    }

    /**
     * Get a list of record sets that are attached to the named $zone
     *
     * @link https://msdn.microsoft.com/en-us/library/azure/mt130638.aspx
     * @param string $zone
     * @return array
     * @throws \Exception
     */
    public function getRecordSetsList($zone)
    {
        if (!is_string($zone) || empty($zone)) {
            throw new \Exception('The zone name must be a non zero length string.');
        }

        $allowedTypes = ["A", "AAAA", "CNAME", "MX", "NS", "SRV", "TXT", "SOA"];
        $output = [];
        $data = $this->getFromAPI(
            'subscriptions/{subscription}/resourceGroups/{group}/providers/Microsoft.Network/dnsZones/{zone}/recordSets',
            ['zone' => $zone],
            '2015-05-04-preview'
        );

        foreach($data as $record) {
            $find = '/providers/Microsoft.Network/dnszones/'. $zone .'/';
            $recordType = substr($record['id'], (strpos($record['id'], $find) + strlen($find)));
            $recordType = explode('/', $recordType);
            $recordType = array_shift($recordType);

            if (! in_array($recordType, $allowedTypes)) {
                throw new \Exception('The type ['. $recordType .'] is not one of: ' . implode(',', $allowedTypes));
            }

            if (! isset($output[$recordType])) {
                $output[$recordType] = [];
            }

            array_push($output[$recordType], $record);
        }unset($record, $data);

        return $output;
    }

    /**
     * @param string $path
     * @param array $attr
     * @param null $version
     * @return array
     * @throws \Exception
     */
    private function getFromAPI($path, array $attr = [], $version = null)
    {
        if (!is_null($version)) {
            $this->azure->API_VERSION = $version;
        }

        $path = $this->buildAPIPath($path, $attr);
        $data = $this->azure->get($path, $this->token);
        $this->throwOnAPIError($data);

        return $data;
    }

    /**
     * @param string $path
     * @param array $attr
     * @param string $body
     * @param null|string $version
     * @return null
     * @throws \Exception
     */
    private function putToAPI($path, array $attr = [], $body, $version = null)
    {
        if (!is_null($version)) {
            $this->azure->API_VERSION = $version;
        }

        $path = $this->buildAPIPath($path, $attr);
        $data = $this->azure->put($path, json_encode($body), $this->token);
        $this->throwOnAPIError($data);

        return $data;
    }

    /**
     * Build the path from an input template string and attributes
     *
     * @param string $path
     * @param array $attr
     * @return mixed
     */
    private function buildAPIPath($path, array $attr = [])
    {
        if (!isset($attr['subscription'])) {
            $attr['subscription'] = $this->configuration['subscription'];
        }

        if (!isset($attr['group'])) {
            $attr['group'] = $this->configuration['group'];
        }

        foreach ($attr as $key => $value) {
            $path = str_replace('{' . $key . '}', $value, $path);
        }
        unset($key, $value);

        return $path;
    }

    /**
     * If the API returns an error message object then we should throw that error
     *
     * @param $data
     * @throws \Exception
     */
    private function throwOnAPIError($data)
    {
        if (is_array($data) && isset($data['error'])) {
            $exceptionClassName = '\\AzureDns\\Exceptions\\' . $data['error']['code'] . 'Exception';
            $exceptionMessage = $data['error']['code'] . ': ' . $data['error']['message'];

            if (class_exists($exceptionClassName)) {
                $exception = new $exceptionClassName($exceptionMessage);
            } else {
                $exception = new \Exception($exceptionMessage);
            }

            throw $exception;
        }
    }
}
