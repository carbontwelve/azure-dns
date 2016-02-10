<?php namespace Carbontwelve\AzureDns;

use Carbontwelve\AzureDns\Events\ResponseSentEvent;
use League\Container\Container;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Event\EmitterAwareInterface;
use League\Event\EmitterAwareTrait;
use League\Route\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class App implements ContainerAwareInterface, EmitterAwareInterface
{

    use ContainerAwareTrait;
    use EmitterAwareTrait;

    /**
     * @var \League\Route\RouteCollection
     */
    private $router;

    public function __construct()
    {
        $this->setContainer(new Container());
    }

    /**
     * @param Request|null $request
     */
    public function run(Request $request = null)
    {
        if (is_null($request)) {
            $request = Request::createFromGlobals();
        }

        $response = $this->handleRequest($request);
        $response->send();
        $this->getEmitter()->emit(new ResponseSentEvent($request, $response));
    }

    /**
     * @param Request $request
     * @return Response
     */
    private function handleRequest(Request $request)
    {
        $this->getContainer()->add(Request::class, $request);

        try{
            $dispatcher = $this->getRouter()->getDispatcher();
            $response = $dispatcher->dispatch(
                $request->getMethod(),
                $request->getPathInfo()
            );
            return $response;
        } catch (\Exception $e) {
            // 404?
            die('404, not found');
        }
    }

    public function getRouter()
    {
        if (!$this->router instanceof RouteCollection) {
            $this->router = new RouteCollection($this->getContainer());
        }
        return $this->router;
    }

    public function get($route, $action)
    {
        $this->getRouter()->addRoute('GET', $route, $action);
    }

    public function post($route, $action)
    {
        $this->getRouter()->addRoute('POST', $route, $action);
    }
}
