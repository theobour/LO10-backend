<?php
require('../config/header.php');
require('../config/conn.php');

// Pour retourner du JSON partout
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


if ($_SERVER['REQUEST_METHOD'] == "POST") {
// Permet de créer une ressource
    $body = json_decode(file_get_contents("php://input"));
    $mdp = md5($body->mdp);
    $sql = $conn->prepare('INSERT INTO utilisateur (prenom,nom,email,naissance,telephone,pseudo,mdp) VALUES (:prenom,:nom,:email,:naissance,:telephone,:pseudo,:mdp)');
    $var = array(
        'prenom' => $body->prenom,
        'nom' => $body->nom,
        'email' => $body->email,
        'naissance' => $body->naissance,
        'telephone' => $body->telephone,
        'pseudo' => $body->pseudo,
        'mdp' => $mdp
    );

    $sql->execute($var);
    header(status_code_header(201));
    echo json_encode(array(
        "success" => true
    ));
}