<?php namespace Carbontwelve\AzureDns;


use League\Container\ContainerInterface;

class App extends \Proton\App
{
    /**
     * The current globally available instance of App
     *
     * @var static
     */
    protected static $instance;

    /**
     * @param ContainerInterface $container
     * @return $this
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        self::setInstance($this);
        return $this;
    }

    /**
     * @return App
     */
    public static function getInstance()
    {
        return static::$instance;
    }

    /**
     * @param App $app
     */
    public static function setInstance(App $app)
    {
        static::$instance = $app;
    }
}
