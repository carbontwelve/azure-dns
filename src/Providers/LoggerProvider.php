<?php

namespace AzureDns\Providers;

use Interop\Container\ContainerInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\Views\PhpRenderer;

class LoggerProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container|ContainerInterface $pimple A container instance
     *
     * @return PhpRenderer
     */
    public function register(Container $pimple)
    {
        $settings = $pimple->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], \Monolog\Logger::DEBUG));
        $pimple['logger'] = $logger;
    }
}
