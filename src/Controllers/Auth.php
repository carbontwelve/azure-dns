<?php namespace Carbontwelve\AzureDns\Controllers;

use Aura\Session\Segment;
use Carbontwelve\AzureDns\ActiveDirectory\AuthenticationContext;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\RedirectResponse;

class Auth
{

    /** @var  \Aura\Session\Segment */
    private $session;

    public function __construct()
    {
        // This is awful but necessary due to the routing engine apparently not using the IoC container
        $this->session = app(Segment::class);
    }

    public function activeDirectory(RequestInterface $request, ResponseInterface $response)
    {
        echo 'howdy'; exit();


        /** @var AuthenticationContext $authenticationContext */
        $authenticationContext = app(AuthenticationContext::class);
        if ( ! $authenticationContext->authenticate() ) {
            $response->getBody()->write('No allowed');
            return $response->withStatus(403);
        }

        echo 'howdy'; exit();

        return new RedirectResponse('https://***REMOVED***/');
    }
}
