<?php namespace AzureDns\Providers;

use AzureDns\AuthenticationContext;
use AzureDns\DNSApi;
use Interop\Container\ContainerInterface;
use Pimple\ServiceProviderInterface;
use Slim\Views\PhpRenderer;
use Aura\Session\Segment;
use Pimple\Container;

class DNSApiProvider implements ServiceProviderInterface
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
        /** @var AuthenticationContext $authContext */
        $authContext = $pimple[AuthenticationContext::class];

        $pimple[DNSApi::class] = new DNSApi(
            $pimple[Segment::class],
            $authContext->getProvider(),
            $authContext->getToken()
        );
    }
}
