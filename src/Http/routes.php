<?php
// Routes

$app->get('/', 'AzureDns\Http\Controllers\DashboardController:index')->setName('home');
$app->get('/azure', 'AzureDns\Http\Controllers\AuthController:authenticate')->setName('azure');

/*
$app->get('/', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
*/
