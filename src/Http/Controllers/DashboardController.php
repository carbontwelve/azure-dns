<?php namespace AzureDns\Http\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use TheNetworg\OAuth2\Client\Provider\Azure;
use Psr\Http\Message\ResponseInterface;

class DashboardController extends BaseController
{
    /**
     * @var Azure
     */
    private $azure;

    /**
     * @var string
     */
    private $token;

    public function __construct(Azure $azure, $token)
    {
        $this->azure = $azure;
        $this->token = $token;
    }

    public function index (ServerRequestInterface $request, ResponseInterface $response, array $args)
    {

        return $this->view('index.phtml', $response);

        // 1. identify the subscription
        $subscriptions = $this->azure->get('subscriptions', $this->token);

        // 2. Identify the resource group
        $groups = $this->azure->get('subscriptions/{subscription}/resourceGroups', $this->token);

        // 3. Identify the DNS Zone
        $zones = $this->azure->get('subscriptions/{subscription}/resourceGroups/{group}/providers/Microsoft.Network/dnsZones', $this->token);

        // 4. Identify record sets for DNS Zone
        $recordSets = $this->azure->get('subscriptions/{subscription}/resourceGroups/{group}/providers/Microsoft.Network/dnsZones/{zone}/recordSets', $this->token);
    }

    public function configure(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        /** @var \Aura\Session\Segment $session */
        $session = $this->container->get(\Aura\Session\Segment::class);

        if (! $configuration = $session->get('configuration')) {
            $configuration = [
                'subscription' => null,
                'group' => null,
            ];
        }

        return $this->view('configure.phtml', $response, [
            'configuration' => $configuration
        ]);
    }
}
