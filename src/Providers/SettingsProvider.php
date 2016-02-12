<?php namespace AzureDns\Providers;

use Interop\Container\ContainerInterface;
use Pimple\ServiceProviderInterface;
use Slim\Views\PhpRenderer;
use Pimple\Container;

class SettingsProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container|ContainerInterface $pimple A container instance
     * @return PhpRenderer
     */
    public function register(Container $pimple)
    {
        $pimple['settings'] = new \Slim\Collection(require __DIR__ . '/../Config/app.php');
    }
}
