<?php namespace AzureDns\Providers;

use AzureDns\Http\Controllers\AuthController;
use AzureDns\Http\Controllers\DashboardController;
use Interop\Container\ContainerInterface;
use Pimple\ServiceProviderInterface;
use Slim\Views\PhpRenderer;
use Pimple\Container;

class ControllerProvider implements ServiceProviderInterface
{
    /** @var array  */
    private $controllers = [];

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
        $this->identifyControllers($pimple);
        /**
         * @var string $id
         * @var \AzureDns\Http\Controllers\BaseController $controller
         */
        foreach ($this->controllers as $id => $controller) {
            $controller->setContainer($pimple);
            $pimple[$id] = $controller;
        }
    }

    private function identifyControllers(Container $pimple)
    {
        /** @var \AzureDns\AuthenticationContext $authContext */
        $authContext = $pimple[\AzureDns\AuthenticationContext::class];

        $this->controllers['AzureDns\Http\Controllers\DashboardController'] = new DashboardController(
            $authContext->getProvider(),
            $authContext->getToken()
        );

        $this->controllers['AzureDns\Http\Controllers\AuthController'] = new AuthController(
            $pimple[\AzureDns\AuthenticationContext::class]
        );
    }
}
