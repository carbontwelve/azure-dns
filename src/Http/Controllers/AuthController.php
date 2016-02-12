<?php namespace AzureDns\Http\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class AuthController extends BaseController
{
    /**
     * @var \AzureDns\AuthenticationContext
     */
    private $authenticationContext;

    /**
     * @var \Aura\Session\Segment
     */
    private $session;

    public function __construct(\AzureDns\AuthenticationContext $authenticationContext, \Aura\Session\Segment $session)
    {
        $this->authenticationContext = $authenticationContext;
        $this->session = $session;
    }

    public function authenticate(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        if (!$this->authenticationContext->authenticate()) {
            return 'There was an issue with active directory authentication!';
        }

        if (!$location = $this->session->get('intendedBeforeRedirect')) {
            $location = $this->container->get('router')->pathFor('home');
        }

        return $response
            ->withStatus(302)
            ->withHeader('Location', $location);
    }
}
