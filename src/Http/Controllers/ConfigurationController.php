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

        if (!$this->configuration = $session->get('configuration')) {
            $this->configuration = [
                'subscription' => null,
                'group' => null,
            ];
            $session->set('configuration', $this->configuration);
        }
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args)
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
            if ($subscription['state'] !== 'Enabled') {
                continue;
            }
            $subscriptions[$subscription['subscriptionId']] = $subscription['displayName'];
        }

        return $this->view('configure_set.phtml', $response, [
            'name' => 'Subscription',
            'data' => $subscriptions
        ]);
    }

    // @todo: validation
    public function postConfigureSubscription(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $this->configuration['subscription'] = $_POST['data'];
        $this->session->set('configuration', $this->configuration);

        return $response
            ->withStatus(301)
            ->withHeader('Location', $this->container->get('router')->pathFor('configure'));
    }

    public function getConfigureGroup(ServerRequestInterface $request, ResponseInterface $response, array $args)
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

        return $this->view('configure_set.phtml', $response, [
            'name' => 'Group',
            'data' => $groups
        ]);
    }

    // @todo: validation
    public function postConfigureGroup(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $this->configuration['group'] = $_POST['data'];
        $this->session->set('configuration', $this->configuration);

        return $response
            ->withStatus(301)
            ->withHeader('Location', $this->container->get('router')->pathFor('configure'));
    }
}
