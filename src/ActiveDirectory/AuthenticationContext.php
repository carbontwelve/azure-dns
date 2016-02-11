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
        if (!session('token')) {
            return false;
        }

        return true;
    }

    public function authenticate()
    {
        if (empty($_GET['state']) || ($_GET['state'] !== session('oauth2state'))) {
            echo session('oauth2state') . " does not equal " . $_GET['state'];
            session()->clear();
            exit();
        }

        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $_GET['code'],
            'resource' => 'https://management.azure.com/'
        ]);

        try {
            $user = $this->provider->get("me", $token);
        } catch (\Exception $e) {
            return false;
        }

        session('user', $user);
        session('token', $token->jsonSerialize());

        return true;
    }

    public function getToken()
    {
        return session('token')['access_token'];
    }

    /**
     * @return array
     */
    public function getUser()
    {
        if (!$this->isAuthenticated()) {
            return [];
        }

        return session('user');
    }

    /**
     * @return Azure
     */
    public function getProvider()
    {
        return $this->provider;
    }
}
