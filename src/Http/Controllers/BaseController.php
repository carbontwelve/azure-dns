<?php namespace AzureDns\Http\Controllers;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

class BaseController
{
    /** @var null|ContainerInterface */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return ContainerInterface|null
     */
    public function getContainer()
    {
        return $this->container;
    }

    protected function view($view, ResponseInterface $response, array $args = [])
    {

        $args['router'] = $this->container->get('router');

        /** @var \Slim\Views\PhpRenderer $renderer */
        $renderer = $this->container['renderer'];
        return $renderer->render($response, $view, $args);
    }
}
