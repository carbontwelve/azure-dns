<?php namespace AzureDns\Http\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use TheNetworg\OAuth2\Client\Provider\Azure;
use Psr\Http\Message\ResponseInterface;

class DashboardController
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
        $subscriptions = $this->azure->get('subscriptions', $this->token);
        var_dump($subscriptions);

        return 'Token: ' . $this->token;
    }
}
