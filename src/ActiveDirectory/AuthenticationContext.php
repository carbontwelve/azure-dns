<?php namespace Carbontwelve\AzureDns\ActiveDirectory;

use TheNetworg\OAuth2\Client\Provider\Azure;

class AuthenticationContext
{

    /**
     * @var Azure
     */
    private $provider;

    /**
     * AuthenticationContext constructor.
     * @param array $config
     * @throws \Exception
     */
    public function __construct(array $config = [])
    {
        if (count($config) === 0) {
            throw new \Exception("The class AuthenticationContext requires config to be set with clientId and clientSecret set.");
        }

        $this->provider = new Azure($config);
    }

    /**
     * @return bool
     */
    public function isAuthenticated()
    {

    }

    public function authenticate()
    {

    }

    public function getToken()
    {

    }

    /**
     * @return array
     */
    public function getUser()
    {
        if (! $this->isAuthenticated()) {
            return [];
        }
    }

    public function getProvider()
    {

    }


}
