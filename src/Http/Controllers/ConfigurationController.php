<?php namespace AzureDns\Http\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use TheNetworg\OAuth2\Client\Provider\Azure;
use Psr\Http\Message\ResponseInterface;

class ConfigurationController extends BaseController
{
    /**
     * @var Azure
     */
    private $azure;

    /**
     * @var string
     */
    private $token;

    /**
     * @var \Aura\Session\Segment
     */
    private $session;

    private $configuration;

    public function __construct(\Aura\Session\Segment $session, Azure $azure, $token)
    {
        $this->azure = $azure;
        $this->token = $token;
        $this->session = $session;

        if (! $this->configuration = $session->get('configuration')) {
            $this->configuration = [
                'subscription' => null,
                'group' => null,
            ];
            $session->set('configuration', $this->configuration);
        }
    }

    public function index (ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        return $this->view('configure_index.phtml', $response, [
            'configuration' => $this->configuration
        ]);
    }

    public function getConfigureSubscription(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $subscriptions = [];
        $data = $this->azure->get('subscriptions', $this->token);
        foreach ($data as $subscription) {
            if ( $subscription['state'] !== 'Enabled') {
                continue;
            }
            $subscriptions[$subscription['subscriptionId']] = $subscription['displayName'];
        }

        return $this->view('configure_set.phtml', $response, [
            'name' => 'Subscription',
            'data' => $subscriptions
        ]);
    }

    public function postConfigureSubscription(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        // ..
    }

    public function getConfigureGroup(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $groups = $this->azure->get('subscriptions/{subscription}/resourceGroups', $this->token);
    }

    public function postConfigureGroup(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        // ..
    }
}
