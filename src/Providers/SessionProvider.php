<?php namespace Carbontwelve\AzureDns\Providers;

use Aura\Session\SessionFactory;
use League\Container\ContainerInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

class SessionProvider extends AbstractServiceProvider
{

    /**
     * @var array
     */
    protected $provides = [
        \Aura\Session\Session::class,
        \Aura\Session\Segment::class
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register()
    {
        $this->container->add(\Aura\Session\Session::class, function(){
            $sessionFactory = new SessionFactory();
            return $sessionFactory->newInstance($_COOKIE);
        });

        $this->container->add(\Aura\Session\Segment::class, function(){
            $session = $this->container->get(\Aura\Session\Session::class);
            return $session->getSegment('Carbontwelve\AzureDns');
        });
    }
}
