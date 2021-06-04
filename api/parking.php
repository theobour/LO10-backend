<?php
require('../config/header.php');
require('../config/conn.php');
require('auth_check.php');
// Pour retourner du JSON partout
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])) {
    // Permet de trouver une ressource avec son id

    $sql = $conn->prepare('SELECT * FROM parking WHERE id = :id');
    $var = array(
        'id' => $_GET['id']
    );
    $sql->execute($var);
    $data = $sql->fetch(PDO::FETCH_ASSOC);
    // Toujours mettre un header
    header(status_code_header($data ? 200 : 404));
    // Si aucune ressource trouvée on renvoie un json vide
    echo $data ? json_encode($data) : '{}';

} elseif ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['aeroport_id'])) {
    // Permet de trouver l'ensemble des parkings d'un aeroport

    $sql = $conn->prepare('SELECT p.*, a.nom as aeroport_nom FROM parking as p,aeroport as a  WHERE a.id = :aeroport_id AND p.aeroport_id = a.id ');
    $var = array(
        'aeroport_id' => $_GET['aeroport_id']
    );
    $sql->execute($var);
    $data = $sql->fetchAll(PDO::FETCH_ASSOC);
    // Toujours mettre un header
    header(status_code_header($data ? 200 : 404));
    // Si aucune ressource trouvée on renvoie un json vide
    echo $data ? json_encode($data) : '{}';

} elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
    // Permet de trouver l'ensemble des parkings
    $sql = $conn->prepare('SELECT *, aeroport.nom as aeroport_nom FROM parking, aeroport WHERE parking.aeroport_id = aeroport.id');
    $sql->execute();
    $data = $sql->fetchAll(PDO::FETCH_ASSOC);
    // Toujours mettre un header
    header(status_code_header($data ? 200 : 404));
    // Si aucune ressource trouvée on renvoie un json vide
    echo $data ? json_encode($data) : '{}';

} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {

// Création d'un nouveau parking
    $auth = check_auth(apache_request_headers(), $conn);
    if ($auth === true) {
        $body = json_decode(file_get_contents("php://input"));
        $sql = $conn->prepare('INSERT INTO parking (nb_places,lieu,aeroport_id,prix,adresse) VALUES (:nb_places,:lieu,:aeroport_id,:prix, :adresse)');
        $var = array(
            "nb_places" => $body->nb_places,
            "lieu" => $body->lieu,
            "aeroport_id" => $body->aeroport_id,
            "prix" => $body->prix,
            "adresse" => $body->adresse
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