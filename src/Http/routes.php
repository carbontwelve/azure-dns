<?php
// Routes

$app->get('/', 'AzureDns\Http\Controllers\DashboardController:index')
    ->setName('home');

$app->get('/azure', 'AzureDns\Http\Controllers\AuthController:authenticate')
    ->setName('azure');

$app->get('/configure', 'AzureDns\Http\Controllers\ConfigurationController:index')
    ->setName('configure');

$app->get('/configure/subscription', 'AzureDns\Http\Controllers\ConfigurationController:getConfigureSubscription')
    ->setName('configureSubscription');

$app->post('/configure/subscription', 'AzureDns\Http\Controllers\ConfigurationController:postConfigureSubscription')
    ->setName('saveConfigureSubscription');

$app->get('/configure/group', 'AzureDns\Http\Controllers\ConfigurationController:getConfigureGroup')
    ->setName('configureGroup');

$app->post('/configure/group', 'AzureDns\Http\Controllers\ConfigurationController:postConfigureGroup')
    ->setName('saveConfigureGroup');

/*
$app->get('/', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
*/
