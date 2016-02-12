<?php namespace AzureDns\Http\Controllers;

use AzureDns\DNSApi;
use Psr\Http\Message\ServerRequestInterface;
use TheNetworg\OAuth2\Client\Provider\Azure;
use Psr\Http\Message\ResponseInterface;

class DashboardController extends BaseController
{
    /**
     * @var \Aura\Session\Segment
     */
    private $session;
    /**
     * @var DNSApi
     */
    private $api;

    public function __construct(\Aura\Session\Segment $session, DNSApi $api)
    {
        $this->session = $session;
        $this->api = $api;
    }

    public function index (ServerRequestInterface $request, ResponseInterface $response, array $args)
    {

        //$zones = $this->api->getRecordSetsList('***REMOVED***');

        return $this->view('index.phtml', $response, [
            'zones' => $this->api->getZonesList()
        ]);

        // 3. Identify the DNS Zone


        // 4. Identify record sets for DNS Zone
        //$recordSets = $this->azure->get('subscriptions/{subscription}/resourceGroups/{group}/providers/Microsoft.Network/dnsZones/{zone}/recordSets', $this->token);
    }

}
