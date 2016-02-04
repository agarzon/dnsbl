<?php
// Routes

$app->get('/[{name}]', function ($request, $response, $args) {
    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get('/score/{ip}', function ($request, $response, $args) {
    $score = Dnsbls\Dnsbls::getScore($args['ip']);
    $response->write(json_encode(['ip' => $args['ip'], 'score' => $score]));
    //$this->logger->info($args['ip'], $score);
    return $response->withHeader('Content-type', 'application/json');
});
$app->get('/validate/{ip}', function ($request, $response, $args) {
    $validate = Dnsbls\Dnsbls::validateIp($args['ip']);
    $ip = $args['ip'];
    if ($validate) {
        $ip = gethostbyname($args['ip']);
    }
    $response->write(json_encode(['ip' => $ip, 'validate' => $validate]));
    return $response->withHeader('Content-type', 'application/json');
});
$app->get('/query/{host}/{ip}', function ($request, $response, $args) {
    $status = (Dnsbls\Dnsbls::checkBl($args['ip'], $args['host'])) ? 'LISTED' : 'OK';
    $response->write(json_encode(['host' => $args['host'], 'status' => $status]));
    return $response->withHeader('Content-type', 'application/json');
});
