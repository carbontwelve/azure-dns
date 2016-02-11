<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

$app = new \Carbontwelve\AzureDns\App();
$app->getContainer()->addServiceProvider(\Carbontwelve\AzureDns\Providers\SessionProvider::class);
$app->getContainer()->addServiceProvider(\Carbontwelve\AzureDns\Providers\AuthProvider::class);

$app->addRequestMiddleware(function (\Zend\Diactoros\ServerRequest $request, \Zend\Diactoros\Response $response) {
    // @var \Carbontwelve\AzureDns\ActiveDirectory\AuthenticationContext $activeDirectory
    $activeDirectory = app(\Carbontwelve\AzureDns\ActiveDirectory\AuthenticationContext::class);
    if (!$activeDirectory->isAuthenticated() && (empty($_GET['state']) || ($_GET['state'] !== session('oauth2state')))) {
        $location = $activeDirectory->getProvider()->getAuthorizationUrl();
        session('oauth2state', $activeDirectory->getProvider()->getState());
        return new \Zend\Diactoros\Response\RedirectResponse($location);
    }
    return $response;
});

$app->getRouter()->map('GET', '/', 'Carbontwelve\AzureDns\Controllers\Dashboard::index')
    ->setName('dashboard');

$app->getRouter()->map('GET', '/azure', 'Carbontwelve\AzureDns\Controllers\Auth::activeDirectory')
    ->setName('azure');

$app->run();
