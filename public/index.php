<?php
session_start();
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

$app = new \Carbontwelve\AzureDns\App();
$app->getContainer()->addServiceProvider(\Carbontwelve\AzureDns\Providers\AuthProvider::class);

$app->addRequestMiddleware( function(){
    header('Location: ' . 'http://www.google.co.uk');
    exit;
});

$app->getRouter()->map('GET', '/', 'Carbontwelve\AzureDns\Controllers\Dashboard::index')
    ->setName('dashboard');

$app->run();
exit();

$provider = new TheNetworg\OAuth2\Client\Provider\Azure([
    'clientId' => getenv('APPSETTING_AD_CLIENT_ID'),
    'clientSecret' => getenv('APPSETTING_AD_KEY'),
    'redirectUri' => 'https://' . getenv('WEBSITE_HOSTNAME') . '/',
    'tenant' => getenv('APPSETTING_AD_TENNANT'),
    'urlAPI' => 'https://management.azure.com/'
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: ' . $authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code'],
        'resource' => 'https://management.azure.com/'
    ]);

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        $me = $provider->get("me", $token);

        // Use these details to create a new profile
        //var_dump($me);

    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh dear...');
    }

    // Use this to interact with an API on the users behalf
    //echo $token->getToken();

    $n = $provider->get('subscriptions', $token->getToken());

    var_dump($n);

}


