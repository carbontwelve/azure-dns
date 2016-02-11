<?php namespace Carbontwelve\AzureDns\Controllers;

use Aura\Session\Segment;
use Carbontwelve\AzureDns\ActiveDirectory\AuthenticationContext;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Dashboard
{

    /** @var  \Aura\Session\Segment */
    private $session;

    public function __construct()
    {
        // This is awful but necessary due to the routing engine apparently not using the IoC container
        $this->session = app(Segment::class);
    }

    public function index(RequestInterface $request, ResponseInterface $response)
    {

        /** @var AuthenticationContext $authenticationContext */
        $authenticationContext = app(AuthenticationContext::class);
        $subscriptions = $authenticationContext->getProvider()->get('subscriptions', $authenticationContext->getToken());
        var_dump($subscriptions);

        $response->getBody()->write('<h1>Hello World</h1>');
        return $response;
    }
}
