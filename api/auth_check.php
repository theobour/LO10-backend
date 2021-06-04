<?php

function check_auth($headers, $conn) {
    $headers = apache_request_headers();
    if (isset($headers['Authorization']) && $headers['Authorization'] !== '') {
        $auth = $headers['Authorization'];
        $splited_auth = preg_split("/ /", $auth);
        if ($splited_auth[0] === "Basic") {
            $decoded_auth = base64_decode($splited_auth[1]);
            $pass = preg_split("/:/", $decoded_auth)[1];
            $pass = md5($pass);
            $pseudo = preg_split("/:/", $decoded_auth)[0];
            $sql = $conn->prepare('SELECT * FROM utilisateur WHERE pseudo = :pseudo AND mdp = :mdp');
            $vars = array(
                'pseudo' => $pseudo,
                "mdp" => $pass
            );
            $sql->execute($vars);
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            if (sizeof($data) === 0) {
                return false;
            } else {
                return true;
            }
        }
    } else {
        return false;
    }
}
