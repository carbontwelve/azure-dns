<?php namespace AzureDns\Http\Middleware;

use Aura\Session\Segment;
use AzureDns\AuthenticationContext;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AzureActiveDirectoryAuthentication
{

    /** @var AuthenticationContext */
    private $authenticationContext;

    /** @var Segment  */
    private $session;

    public function __construct(AuthenticationContext $authenticationContext, Segment $session)
    {
        $this->authenticationContext = $authenticationContext;
        $this->session = $session;
    }

    /**
     * Example middleware invokable class
     *
     * @param  ServerRequestInterface $request  PSR7 request
     * @param  ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        // If not authenticated
        if (!$this->authenticationContext->isAuthenticated() && (empty($_GET['state']) || ($_GET['state'] !== $this->session->get('oauth2state'))))
        {
            $location = $this->authenticationContext->getProvider()->getAuthorizationUrl();
            $this->session->set('oauth2state', $this->authenticationContext->getProvider()->getState());
            $this->session->set('intendedBeforeRedirect', (string) $request->getUri());
            return $response
                ->withStatus(301)
                ->withHeader('Location', $location);
        }

        // If authenticated
        return $next($request, $response);
    }

}
