<?php namespace AzureDns\Http\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class AuthController extends BaseController
{

    /**
     * @var \AzureDns\AuthenticationContext
     */
    private $authenticationContext;

    public function __construct(\AzureDns\AuthenticationContext $authenticationContext)
    {
        $this->authenticationContext = $authenticationContext;
    }

    public function authenticate (ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        if (!$this->authenticationContext->authenticate()) {
            return 'There was an issue with active directory authentication!';
        }

        return $response
            ->withStatus(302)
            ->withHeader('Location', $this->container->get('router')->pathFor('home') );
    }
}
