<?php
// Routes

// $app->get('/[{name}]', function ($request, $response, $args) {
//     // Sample log message
//     $this->logger->info("Slim-Skeleton '/' route");

//     // Render index view
//     return $this->renderer->render($response, 'index.phtml', $args);
// });

$app->get('/', 'Api\Controller\Oauth:test');
$app->post('/test', 'Api\Controller\Oauth:index');
$app->post('/v1/oauth/register.html', 'Api\Controller\v1\Oauth:register');
$app->post('/v1/oauth/authorize.html', 'Api\Controller\v1\Oauth:authorize');