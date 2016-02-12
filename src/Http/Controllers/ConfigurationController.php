<?php namespace AzureDns\Http\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use AzureDns\DNSApi;

class ConfigurationController extends BaseController
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
        return $this->view('configure_index.phtml', $response, [
            'configuration' => $this->api->getConfiguration()
        ]);
    }

    public function getConfigureSubscription(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        return $this->view('configure_set.phtml', $response, [
            'name' => 'Subscription',
            'data' => $this->api->getSubscriptionsList()
        ]);
    }

    // @todo: validation
    public function postConfigureSubscription(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $this->api->setConfig('subscription', $_POST['data']);

        return $response
            ->withStatus(301)
            ->withHeader('Location', $this->container->get('router')->pathFor('configure'));
    }

    public function getConfigureGroup(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        return $this->view('configure_set.phtml', $response, [
            'name' => 'Group',
            'data' => $this->api->getGroupsList()
        ]);
    }

    // @todo: validation
    public function postConfigureGroup(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $this->api->setConfig('group', $_POST['data']);

        return $response
            ->withStatus(301)
            ->withHeader('Location', $this->container->get('router')->pathFor('configure'));
    }
}
