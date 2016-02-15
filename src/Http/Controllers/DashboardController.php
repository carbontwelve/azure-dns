<?php namespace AzureDns\Http\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use AzureDns\DNSApi;

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
        // Redirect to configuration if not yet configured
        if (!$this->api->configurationIsValid()) {
            return $response
                ->withStatus(301)
                ->withHeader('Location', $this->container->get('router')->pathFor('configure'));
        }

        return $this->view('index.phtml', $response, [
            'zones' => $this->api->getZonesList()
        ]);

        // 4. Identify record sets for DNS Zone
        //$recordSets = $this->azure->get('subscriptions/{subscription}/resourceGroups/{group}/providers/Microsoft.Network/dnsZones/{zone}/recordSets', $this->token);
    }

    public function recordSets(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        dd($args);
    }
}
