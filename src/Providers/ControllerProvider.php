<?php

namespace AzureDns\Providers;

use AzureDns\Http\Controllers\AuthController;
use AzureDns\Http\Controllers\ConfigurationController;
use AzureDns\Http\Controllers\RecordSetController;
use AzureDns\Http\Controllers\ZoneController;
use Interop\Container\ContainerInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\Views\PhpRenderer;

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
     *
     * @return PhpRenderer
     */
    public function register(Container $pimple)
    {
        $this->identifyControllers($pimple);
        /**
         * @var string
         * @var \AzureDns\Http\Controllers\BaseController $controller
         */
        foreach ($this->controllers as $id => $controller) {
            $controller->setContainer($pimple);
            $pimple[$id] = $controller;
        }
    }

    private function identifyControllers(Container $pimple)
    {
        $this->controllers['AzureDns\Http\Controllers\ZoneController'] = new ZoneController(
            $pimple[\Aura\Session\Segment::class],
            $pimple[\AzureDns\DNSApi::class]
        );

        $this->controllers['AzureDns\Http\Controllers\RecordSetController'] = new RecordSetController(
            $pimple[\Aura\Session\Segment::class],
            $pimple[\AzureDns\DNSApi::class]
        );

        $this->controllers['AzureDns\Http\Controllers\AuthController'] = new AuthController(
            $pimple[\AzureDns\AuthenticationContext::class],
            $pimple[\Aura\Session\Segment::class]
        );

        $this->controllers['AzureDns\Http\Controllers\ConfigurationController'] = new ConfigurationController(
            $pimple[\Aura\Session\Segment::class],
            $pimple[\AzureDns\DNSApi::class]
        );
    }
}
