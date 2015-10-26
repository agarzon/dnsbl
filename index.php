<?php

require 'vendor/autoload.php';
require 'functions.php';

// Create container
$container = new \Slim\Container;

// Register component on container
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig('templates', [
        'cache' => false
    ]);

    $view->addExtension(new \Slim\Views\TwigExtension(
        $c['router'],
        $c['request']->getUri()
    ));

    return $view;
};

$app = new \Slim\App($container);

$app->get('/', function ($request, $response, $args) {
    return $this->view->render($response, 'index.html', [

    ]);
});

$app->get('/score/{ip}', function ($request, $response, $args) {
    $score = Dnsbls::getScore($args['ip']);
    $response->write(json_encode(['ip' => $args['ip'], 'score' => $score]));
    return $response->withHeader('Content-type', 'application/json');
});

$app->get('/validate/{ip}', function ($request, $response, $args) {
    $validate = Dnsbls::validateIp($args['ip']);

    $ip = $args['ip'];
    if ($validate) {
        $ip = gethostbyname($args['ip']);
    }

    $response->write(json_encode(['ip' => $ip, 'validate' => $validate]));
    return $response->withHeader('Content-type', 'application/json');
});

$app->get('/query/{host}/{ip}', function ($request, $response, $args) {
    $status = (Dnsbls::checkBl($args['ip'], $args['host'])) ? 'LISTED' : 'OK';
    $response->write(json_encode(['host' => $args['host'], 'status' => $status]));
    return $response->withHeader('Content-type', 'application/json');
});

$app->get('/test', function ($request, $response, $args) {
    $x = Dnsbls::validateIp('google.ca');
    //$x = Dnsbls::validateIp('2.2.2.2');
    var_dump($x);
});

$app->run();
