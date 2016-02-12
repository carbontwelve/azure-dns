<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

$app->add(new \AzureDns\Http\Middleware\AzureActiveDirectoryAuthentication(
    $app->getContainer()->get(\AzureDns\AuthenticationContext::class),
    $app->getContainer()->get(\Aura\Session\Segment::class)
));
