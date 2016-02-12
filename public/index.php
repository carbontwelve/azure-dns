<?php require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

session_start();

// Instantiate the container
$container = new \Slim\Container;

// Load settings into the container
$container->register(new \AzureDns\Providers\SettingsProvider());
$container->register(new \AzureDns\Providers\SessionProvider());
$container->register(new \AzureDns\Providers\AuthProvider());
$container->register(new \AzureDns\Providers\ViewProvider());
$container->register(new \AzureDns\Providers\LoggerProvider());
$container->register(new \AzureDns\Providers\DNSApiProvider());
$container->register(new \AzureDns\Providers\ControllerProvider());

// Instantiate the app
$app = new \Slim\App($container);

// Register middleware
require __DIR__ . '/../src/Http/middleware.php';

// Register routes
require __DIR__ . '/../src/Http/routes.php';

// Run app
$app->run();
