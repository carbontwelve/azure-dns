<?php

//
// DNS Zone Management Routes
//
$app->get('/', 'AzureDns\Http\Controllers\ZoneController:index')
    ->setName('zoneIndex');

$app->get('/zone/create', 'AzureDns\Http\Controllers\ZoneController:create')
    ->setName('createZone');

$app->post('/zone/create', 'AzureDns\Http\Controllers\ZoneController:store')
    ->setName('storeZone');

$app->get('/zone/{zone}/destroy', 'AzureDns\Http\Controllers\ZoneController:destroy')
    ->setName('destroyZone');

//
// DNS Record Set Management Routes
//

$app->get('/zone/{zone}/record-sets', 'AzureDns\Http\Controllers\RecordSetController:index')
    ->setName('viewZoneRecordSets');

$app->get('/zone/{zone}/record-sets/create', 'AzureDns\Http\Controllers\RecordSetController:create')
    ->setName('createZoneRecordSets');

$app->post('/zone/{zone}/record-sets/create', 'AzureDns\Http\Controllers\RecordSetController:store')
    ->setName('storeZoneRecordSets');

$app->post('/zone/{zone}/record-sets/update', 'AzureDns\Http\Controllers\RecordSetController:update')
    ->setName('updateZoneRecordSets');

//
// Active Directory Auth Routes
//
$app->get('/azure', 'AzureDns\Http\Controllers\AuthController:authenticate')
    ->setName('azure');

//
// Configuration Routes
//
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
