<?php

namespace dig_deeper;
use \Firebase\JWT\JWT;
use \PDO;

class Auth
{
    private $view;
    private $db;
    private $router;
    private $secrettoken;

    function __construct($view, $db, $router, $secrettoken) {
        $this->view = $view;
        $this->db = $db;
        $this->router = $router;
        $this->secrettoken = $secrettoken;
    }

    public function authenticate($request, $response, $args) {
        $data = $request->getParsedBody();
        $login = $data['userid'];
        $password = $data['password'];
        $sql = 'SELECT * FROM `user`
            WHERE (
                `userid` = ?
            ) AND (
                `password` = PASSWORD(?)
            )';
        $query=$this->db->prepare($sql);
        $param = array ($login, $password);
        $query->execute($param);
        if ($row=$query->fetch(PDO::FETCH_OBJ)) {
            $payload = array("userid" => $row->userid);
            $token = JWT::encode($payload, $this->secrettoken, "HS256");
            setcookie("authtoken", $token, time()+3600);  // cookie expires in one hour
            return $response->withRedirect($this->router->pathFor('studies'))->withStatus(302);
        } else {
            // echo json_encode("Invalid username or password.");
            global $app;
            $app->getContainer()->flash->addMessage('message', 'Invalid username or password.');
            return $response->withRedirect($this->router->pathFor('login'))->withStatus(302);
        }
    }

    public function logout($request, $response, $args) {
        setcookie("authtoken", "", time()-3600);
        return $this->view->render($response, 'home.html');
    }
}

?>
