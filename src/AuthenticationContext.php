<?php namespace AzureDns;

use Aura\Session\Segment;
use TheNetworg\OAuth2\Client\Provider\Azure;

class AuthenticationContext
{

    /**
     * @var Azure
     */
    private $provider;

    /** @var Segment */
    private $session;

    /**
     * AuthenticationContext constructor.
     * @param array $config
     * @throws \Exception
     */
    public function __construct(array $config = [], Segment $session)
    {
        if (count($config) === 0) {
            throw new \Exception("The class AuthenticationContext requires config to be set with clientId and clientSecret set.");
        }

        $this->provider = new Azure($config);
        $this->session = $session;
    }

    /**
     * @return bool
     */
    public function isAuthenticated()
    {
        if (!$this->session->get('token')) {
            return false;
        }

        return true;
    }

    public function authenticate()
    {
        if (empty($_GET['state']) || ($_GET['state'] !== $this->session->get('oauth2state'))) {
            echo $this->session->get('oauth2state') . " does not equal " . $_GET['state'];
            $this->session->clear();
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

        $this->session->set('user', $user);
        $this->session->set('token', $token->jsonSerialize());

        return true;
    }

    public function getToken()
    {
        return $this->session->get('token')['access_token'];
    }

    /**
     * @return array
     */
    public function getUser()
    {
        if (!$this->isAuthenticated()) {
            return [];
        }

        return $this->session->get('user');
    }

    /**
     * @return Azure
     */
    public function getProvider()
    {
        return $this->provider;
    }
}
