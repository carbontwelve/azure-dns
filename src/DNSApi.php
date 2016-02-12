<?php namespace AzureDns;

use TheNetworg\OAuth2\Client\Provider\Azure;
use Aura\Session\Segment;

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

    public function getConfig($key)
    {
        if (! in_array($key, ['subscription', 'group'])) {
            throw new \Exception('['. $key .'] is not a valid configuration key');
        }

        return $this->configuration[$key];
    }

    public function setConfig($key, $value)
    {
        if (! in_array($key, ['subscription', 'group'])) {
            throw new \Exception('['. $key .'] is not a valid configuration key');
        }

        $this->configuration[$key] = $value;
        $this->session->set('configuration', $this->configuration);
    }

    /**
     * @todo make this aware when the api returns an error, and throw it
     * @return array
     */
    public function getSubscriptionsList()
    {
        $subscriptions = [];
        $data = $this->azure->get('subscriptions', $this->token);
        foreach ($data as $subscription) {
            if ($subscription['state'] !== 'Enabled') {
                continue;
            }
            $subscriptions[$subscription['subscriptionId']] = $subscription['displayName'];
        }
        return $subscriptions;
    }

    public function getGroupsList()
    {
        $this->azure->API_VERSION = '2015-11-01';
        $groups = [];
        $data = $this->azure->get(
            'subscriptions/' . $this->configuration['subscription'] . '/resourceGroups',
            $this->token
        );

        foreach ($data as $group) {
            $groups[$group['name']] = $group['name'];
        }

        return $groups;
    }

    public function getZonesList()
    {
        $this->azure->API_VERSION = '2015-05-04-preview';
        return $this->azure->get(
            'subscriptions/' . $this->configuration['subscription'] . '/resourceGroups/' . $this->configuration['group'] . '/providers/Microsoft.Network/dnsZones',
            $this->token
        );
    }

    public function getRecordSetsList($zone)
    {
        $this->azure->API_VERSION = '2015-05-04-preview';
        return $this->azure->get('subscriptions/' . $this->configuration['subscription'] . '/resourceGroups/' . $this->configuration['group'] . '/providers/Microsoft.Network/dnsZones/'. $zone .'/recordSets', $this->token);
    }

}
