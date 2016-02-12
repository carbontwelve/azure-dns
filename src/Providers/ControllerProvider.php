<?php namespace AzureDns\Providers;

use AzureDns\Http\Controllers\AuthController;
use AzureDns\Http\Controllers\DashboardController;
use Interop\Container\ContainerInterface;
use Pimple\ServiceProviderInterface;
use Slim\Views\PhpRenderer;
use Pimple\Container;

class ControllerProvider implements ServiceProviderInterface
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
        /** @var \AzureDns\AuthenticationContext $authContext */
        $authContext = $pimple[\AzureDns\AuthenticationContext::class];

        $pimple['AzureDns\Http\Controllers\DashboardController'] = new DashboardController(
            $authContext->getProvider(),
            $authContext->getToken()
        );

        $pimple['AzureDns\Http\Controllers\AuthController'] = new AuthController(
            $pimple[\AzureDns\AuthenticationContext::class]
        );
    }
}
