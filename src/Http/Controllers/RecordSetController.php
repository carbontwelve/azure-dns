<?php namespace AzureDns\Http\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use AzureDns\DNSApi;

class RecordSetController extends BaseController
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

    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        // Redirect to configuration if not yet configured
        if (!$this->api->configurationIsValid()) {
            return $response
                ->withStatus(301)
                ->withHeader('Location', $this->container->get('router')->pathFor('configure'));
        }

        dd ($args);

        return $this->view('index.phtml', $response, [
            'zones' => $this->api->getZonesList()
        ]);
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {

    }

    public function store(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {

    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {

    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {

    }
}
