<?php namespace AzureDns\Http\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use AzureDns\DNSApi;

class ZoneController extends BaseController
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

        return $this->view('zones\index.phtml', $response, [
            'zones' => $this->api->getZonesList()
        ]);
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        return $this->view('zones\create.phtml', $response, []);
    }

    public function store(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $input = $request->getParsedBody();

        if (!isset($input['zone']) || empty($input['zone'])) {

            $this->session->setFlash('error', 'Zone name must be a non-empty string.');
            $this->session->setFlash('old', $input);

            return $response
                ->withStatus(301)
                ->withHeader('Location', $this->container->get('router')->pathFor('createZone'));
        }

        // Attempt to add zone

        $data = $this->api->createZone($input['zone']);

        dd($data);
    }
}
