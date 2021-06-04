<?php
require('../config/header.php');
require('../config/conn.php');
require('auth_check.php');

// Pour retourner du JSON partout
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])) {
    // Permet de trouver une ressource avec son id

    $sql = $conn->prepare('SELECT * FROM vehicule WHERE id = :id');
    $var = array(
        'id' => $_GET['id']
    );
    $sql->execute($var);
    $data = $sql->fetch(PDO::FETCH_ASSOC);
    // Toujours mettre un header
    header(status_code_header($data ? 200 : 404));
    // Si aucune ressource trouvée on renvoie un json vide
    echo $data ? json_encode($data) : '{}';

} elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
    // Permet de trouver l'ensemble des ressources

    $sql = $conn->prepare('SELECT * FROM aeroport');
    $sql->execute();
    $data = $sql->fetchAll(PDO::FETCH_ASSOC);
    // Toujours mettre un header
    header(status_code_header($data ? 200 : 404));
    // Si aucune ressource trouvée on renvoie un array vide
    echo $data ? json_encode($data) : '[]';

} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {

// Création d'un nouvel aeroport
    $auth = check_auth(apache_request_headers(), $conn);
    if ($auth === true) {
        $body = json_decode(file_get_contents("php://input"));
        $sql = $conn->prepare('INSERT INTO aeroport (nom,code) VALUES (:nom,:code)');
        $var = array(
            'nom' => $body->nom,
            'code' => $body->code
        );
        $response = $sql->execute($var);
        header(status_code_header(201));
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
