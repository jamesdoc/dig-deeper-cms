<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$settings = require __DIR__.'/../../cfg/settings.php';
$JsonDownloadCount = $settings['settings']['JsonDownloadCount'];

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

$app->get('/json', function (Request $request, Response $response, array $args) {
    global $JsonDownloadCount;
    $sql = "SELECT
            `study`.*,
            `week`.`name` AS `week_name`,
            `author`.`title` AS `author_title`,
            `author`.`first_name`,
            `author`.`last_name`,
            `author`.`avatar`
            FROM `study`
            JOIN `author` ON `study`.`author_id` = `author`.`id`
            JOIN `week` ON `study`.`week_id` = `week`.`id`
            ORDER BY `study`.`week_id` DESC, `study`.`day_no` ASC
            LIMIT ".$JsonDownloadCount;
    $query=$this->db->prepare($sql);
    $query->execute();

    $data = array('meta' => array('title' => 'Dig Deeper Devotions'), 'data' => array());
    $weeks = array();

    while ($study=$query->fetch(PDO::FETCH_OBJ)) {
        if (!array_key_exists($study->week_name, $weeks)) {
            $weeks[$study->week_name] = array();
        }
        $weeks[$study->week_name][] =
            array('id' => $study->id,
                  'publish_date' => $study->publish_date,
                  'title' => $study->title,
                  'passage_ref' => $study->passage_ref,
                  'passage_text' => $study->passage_text,
                  'text_content' => array(
                    'prayer_intro' => $study->prayer_intro,
                    'body' => $study->body,
                    'prayer_outro' => $study->prayer_outro,
                    ),
                  'video_url' => $study->video_url,
                  'audio_url' => $study->audio_url,
                  'author' => array(
                    'first_name' => $study->first_name,
                    'last_name' => $study->last_name,
                    'title' => $study->author_title,
                    'avatar' => $study->avatar));
    }

    foreach ($weeks as $name => $studies) {
        $data['data'][] = array('week' => $name, 'days' => $studies);
    }

    $content = $response->getBody();
    $content->write(json_encode($data));

    return $response->withHeader('Content-Type', 'application/json')
                    ->withHeader('Content-Transfer-Encoding', 'UTF-8')
                    ->withHeader('Expires', '0')
                    ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                    ->withHeader('Pragma', 'public');
})->setName('json');
    
// Add a study
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
