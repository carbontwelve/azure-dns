<?php namespace Carbontwelve\AzureDns\Providers;

use Carbontwelve\AzureDns\ActiveDirectory\AuthenticationContext;
use League\Container\ServiceProvider\AbstractServiceProvider;

class AuthProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        AuthenticationContext::class
    ];

    /**
     * Register the Authentication Context for Active Directory authentication
     * @return void
     */
    public function register()
    {
        $this->getContainer()->add(AuthenticationContext::class, function(){
            return new AuthenticationContext([
                'clientId' => getenv('APPSETTING_AD_CLIENT_ID'),
                'clientSecret' => getenv('APPSETTING_AD_KEY'),
                'redirectUri' => 'https://' . getenv('WEBSITE_HOSTNAME') . '/',
                'tenant' => getenv('APPSETTING_AD_TENNANT'),
                'urlAPI' => 'https://management.azure.com/'
            ]);
        });
    }
}
