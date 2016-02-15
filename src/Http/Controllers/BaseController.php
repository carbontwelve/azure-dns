<?php namespace AzureDns\Http\Controllers;
;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Aura\Session\Segment;

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
        /** @var Segment $session */
        $session = $this->container->get(Segment::class);

        $args['router'] = $this->container->get('router');
        $args['old_input'] = $session->getFlash('old');

        $messageBag = [];
        $messageBag['error'] = $session->getFlash('error');
        $messageBag['success'] = $session->getFlash('success');
        $args['message_bag'] = $messageBag;

        /** @var \Slim\Views\PhpRenderer $renderer */
        $renderer = $this->container['renderer'];
        return $renderer->render($response, $view, $args);
    }
}
