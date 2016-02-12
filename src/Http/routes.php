<?php
// Routes

$app->get('/', 'AzureDns\Http\Controllers\DashboardController:index');
$app->get('/azure', 'AzureDns\Http\Controllers\AuthController:authenticate');

/*
$app->get('/', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
*/
