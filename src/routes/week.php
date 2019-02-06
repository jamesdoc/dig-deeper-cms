<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/week', function (Request $request, Response $response, array $args) {
    $userid = $request->getAttribute('userid');

    return $this->view->render($response, 'week.html', [
        'studies' => $studies,
        'loggedin' => True
    ]);
})->setName('week');

?>
