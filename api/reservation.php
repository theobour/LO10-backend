<?php
require('../config/header.php');
require('../config/conn.php');

// Pour retourner du JSON partout
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == "POST") {

// Permet de créer une ressource
    $body = json_decode(file_get_contents("php://input"));
    $sql = $conn->prepare('INSERT INTO reservation (voiture_id,locataire_id,parking_id,date_debut,date_fin) VALUES (:voiture_id,:locataire_id,:parking_id,:date_debut,:date_fin)');
    $var = array(
        'voiture_id' => $body->voiture_id,
        'locataire_id' => $body->locataire_id,
        'parking_id' => $body->parking_id,
        'date_debut' => $body->date_debut,
        'date_fin' => $body->date_fin
    );
    $response = $sql->execute($var);
    header(status_code_header(201));
    echo json_encode(array(
        "success" => true
    ));
}   else {
    // Retourne mauvaise requête si aucune des méthodes précédentes
    header(status_code_header(404));
}
?>
