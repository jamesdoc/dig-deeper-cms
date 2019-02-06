<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function (Request $request, Response $response, array $args) {
    if($request->getAttribute('userid') !== NULL)
        return $response->withRedirect($this->router->pathFor("studies"))->withStatus(302);
    return $this->view->render($response, 'home.html');
})->setName('home');

?>
