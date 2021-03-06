<?php
require('../config/header.php');
require('../config/conn.php');
require('auth_check.php');
// Pour retourner du JSON partout
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])) {
    $sql = $conn->prepare('SELECT * FROM utilisateur WHERE id = :id');
    $sql->execute(array(
        'id' => $_GET['id']
    ));
    $data = $sql->fetch(PDO::FETCH_ASSOC);
    // Toujours mettre un header
    header(status_code_header($data ? 200 : 404));
    // Si aucune ressource trouvée on renvoie un array vide
    echo $data ? json_encode($data) : '[]';
} elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
    $sql = $conn->prepare('SELECT * FROM utilisateur');
    $sql->execute();
    $data = $sql->fetchAll(PDO::FETCH_ASSOC);
    // Toujours mettre un header
    header(status_code_header($data ? 200 : 404));
    // Si aucune ressource trouvée on renvoie un array vide
    echo $data ? json_encode($data) : '[]';
} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
// Permet de créer une ressource
    $body = json_decode(file_get_contents("php://input"));
    $password = md5($body->password);
    $pseudo = $body->pseudo;
    $sql = $conn->prepare('SELECT * FROM utilisateur WHERE pseudo = :pseudo AND mdp = :password');
    $var = array(
        'password' => $password,
        "pseudo" => $pseudo
    );
    $sql->execute($var);
    $data = $sql->fetch(PDO::FETCH_ASSOC);
    if ($data) {
        header(status_code_header(201));
        echo json_encode($data);
    } else {
        header(status_code_header(404));
        echo 'false';
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "PUT" && isset($_GET['id'])) {
    // Permet de modifier le profil d'un utilisateur
    $auth = check_auth(apache_request_headers(), $conn);
    if ($auth === true) {
        $body = json_decode(file_get_contents("php://input"));
        $sqlDelete = $conn->prepare('UPDATE utilisateur SET prenom = :prenom, nom = :nom, email = :email, naissance = :naissance, telephone = :telephone  WHERE id = :id');
        $array = array(
            'prenom' => $body->prenom,
            'nom' => $body->nom,
            'email' => $body->email,
            'password' => md5($body->password),
            'naissance' => $body->naissance,
            'telephone' => $body->telephone,
            'id' => $_GET['id']
        );
        $sqlDelete->execute($array);
        // Toujours mettre un header
        header(status_code_header(200));
        // Si aucune ressource trouvée on renvoie un array vide
        echo json_encode(array(
            "success" => true
        ));
    } else {
        header(status_code_header(401));
        return;
    }
} else {
    // Retourne mauvaise requête si aucune des méthodes précédentes
    header(status_code_header(404));
}
?>
