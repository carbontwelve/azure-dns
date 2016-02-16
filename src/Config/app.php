<?php
return [
    'debug' => true,
    'whoops.editor' => 'sublime',

    'displayErrorDetails' => true, // set to false in production
    'determineRouteBeforeAppMiddleware' => false,
    'outputBuffering' => 'append',
    'responseChunkSize' => 4096,
    'httpVersion' => '1.1',

    // Renderer settings
    'renderer' => [
        'template_path' => realpath(__DIR__ . '/../../views') . DIRECTORY_SEPARATOR,
    ],

    // Monolog settings
    'logger' => [
        'name' => 'slim-app',
        'path' => realpath(__DIR__ . '/../../logs/') . DIRECTORY_SEPARATOR . 'app.log'
    ],
];
