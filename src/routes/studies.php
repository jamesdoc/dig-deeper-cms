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

?>
