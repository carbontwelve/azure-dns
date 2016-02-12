<?php namespace AzureDns\Http\Controllers;

use Interop\Container\ContainerInterface;

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
}
