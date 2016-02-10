<?php namespace Carbontwelve\AzureDns\Controllers;

use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response;

class Dashboard
{
    public function index(RequestInterface $request, Response $response)
    {
        $response->getBody()->write('<h1>Hello World</h1>');
        return $response;
    }
}