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

        return $this->view('record-sets/index.phtml', $response, [
            'zone' => $args['zone'],
            'records' => $this->api->getRecordSetsList($args['zone'])
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
        $input = $request->getParsedBody();

        $output = [
            'location' => 'global',
            'tags' => [],
            'properties' => [
                "TTL" => $input['meta']['TTL']
            ]
        ];

        $output['properties'][$input['meta']['type']] = $input[$input['meta']['type']];

        header('Content-Type: text/plain');
        echo json_encode($output);
        die();
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {

    }
}
