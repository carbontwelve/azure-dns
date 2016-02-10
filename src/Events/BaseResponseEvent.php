<?php namespace Carbontwelve\AzureDns\Events;

use League\Event\AbstractEvent;
use League\Event\EventInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseResponseEvent extends AbstractEvent implements EventInterface
{
    /**
     * @var Response
     */
    private $response;
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request, Response $response)
    {
        $this->response = $response;
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
