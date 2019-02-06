<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
$log = $app->log;

// Lists the studies
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

// Add a studie
$app->post('/study', function (Request $request, Response $response) {
    $data = $request->getParsedBody();

    $sql = "INSERT INTO study (author_id, week_id, day_no, publish_date, title, passage_ref, passage_text, prayer_intro, body, prayer_outro, audio_url, video_url) 
            VALUES (:author_id, :week_id, :day_no, :publish_date, :title, :passage_ref, :passage_text, :prayer_intro, :body, :prayer_outro, :audio_url, :video_url)";
    
    $stmt = $this->db->prepare($sql);

    $stmt->bindParam("author_id", $data['author_id']);
    $stmt->bindParam("week_id", $data['week_id']);
    $stmt->bindParam("day_no", $data['day_no']);
    $stmt->bindParam("publish_date", $data['publish_date']);
    $stmt->bindParam("title", $data['title']);
    $stmt->bindParam("passage_ref", $data['passage_ref']);
    $stmt->bindParam("passage_text", $data['passage_text']);
    $stmt->bindParam("prayer_intro", $data['prayer_intro']);
    $stmt->bindParam("body", $data['body']);
    $stmt->bindParam("prayer_outro", $data['prayer_outro']);
    $stmt->bindParam("audio_url", $data['audio_url']);
    $stmt->bindParam("video_url", $data['video_url']);

    $stmt->execute();

});

?>
