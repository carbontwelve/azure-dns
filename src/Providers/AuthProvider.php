<?php

namespace AzureDns\Providers;

use Aura\Session\Segment;
use AzureDns\AuthenticationContext;
use Interop\Container\ContainerInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\Views\PhpRenderer;

class AuthProvider implements ServiceProviderInterface
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
        $pimple[AuthenticationContext::class] = new AuthenticationContext([
            'clientId'     => getenv('APPSETTING_AD_CLIENT_ID'),
            'clientSecret' => getenv('APPSETTING_AD_KEY'),
            'redirectUri'  => 'https://'.getenv('WEBSITE_HOSTNAME').'/azure',
            'tenant'       => getenv('APPSETTING_AD_TENNANT'),
            'urlAPI'       => 'https://management.azure.com/',
        ], $pimple[Segment::class]);
    }
}
