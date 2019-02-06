<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/studies', function (Request $request, Response $response, array $args) {
    $userid = $request->getAttribute('userid');

    $sql = "SELECT *
            FROM `study`";
    $query=$this->db->prepare($sql);
    $query->execute();
    $studies = $query->fetchAll();

    return $this->view->render($response, 'studies.html', [
        'studies' => $studies,
        'loggedin' => True
    ]);
})->setName('studies');

?>
