<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__.'/../classes/Auth.php';

$app->get('/login', function (Request $request, Response $response, array $args) {
    $allgetvars = $request->getQueryParams();
    if (array_key_exists('message', $allgetvars) && $allgetvars['message'] == 'invalid') {
        return $this->view->render($response, 'login.html', ['message' => 'invalid']);
    }
    return $this->view->render($response, 'login.html');
})->setName('login');

$app->post('/authenticate', 'dig_deeper\Auth:authenticate')->setName('authenticate');

$app->get('/logout', 'dig_deeper\Auth:logout')->setName('logout');

?>
