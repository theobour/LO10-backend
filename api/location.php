<?php


require('../config/header.php');
require('../config/conn.php');

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

} elseif ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['aeroport_id']) && isset($_GET['date_debut']) && isset($_GET['date_fin'])) {
    // Permet de trouver l'ensemble des réservations possible pour un aéroport

    $sql = $conn->prepare('SELECT v.id as voiture_id, v.type, v.couleur, v.marque, v.nb_place, v.etat, p.adresse, p.lieu, a.nom, p.id as parking_id, a.id as aeroport_id 
FROM voiture as v, parking as p, aeroport as a, location as l
WHERE a.id = :aeroport_loc AND a.id = p.aeroport_id AND l.parking_id = p.id AND debut_disponibilite <= :date_entree AND fin_disponibilite >= :date_sortie');
    $vars = array(
        'aeroport_loc' => $_GET['aeroport_id'],
        'date_entree' => $_GET['date_debut'],
        'date_sortie' => $_GET['date_fin']
    );
    $sql->execute($vars);
    $data = $sql->fetchAll(PDO::FETCH_ASSOC);
    // Toujours mettre un header
    header(status_code_header($data ? 200 : 404));
    // Si aucune ressource trouvée on renvoie un array vide
    echo $data ? json_encode($data) : '[]';
} elseif ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['utilisateur_id'])) {
    // Permet de trouver l'ensemble des réservations possible pour un aéroport

    $sql = $conn->prepare('SELECT l.*, p.*, a.*, l.prix as prix_location
FROM location as l, voiture as v, utilisateur as u, parking as p, aeroport as a
            WHERE l.voiture_id = v.id AND v.proprietaire_id = u.id AND u.id = :utilisateur_id AND l.parking_id = p.id AND p.aeroport_id = a.id');
    $vars = array(
        'utilisateur_id' => $_GET['utilisateur_id'],
    );
    $sql->execute($vars);
    $data = $sql->fetchAll(PDO::FETCH_ASSOC);
    // Toujours mettre un header
    header(status_code_header($data ? 200 : 404));
    // Si aucune ressource trouvée on renvoie un array vide
    echo $data ? json_encode($data) : '[]';
} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {

// Permet de créer une location
    $body = json_decode(file_get_contents("php://input"));
    $sql = $conn->prepare('INSERT INTO location (voiture_id,parking_id,debut_disponibilite,fin_disponibilite,prix) VALUES (:voiture_id,:parking_id,:debut_disponibilite,:fin_disponibilite,:prix)');
    $var = array(
        "voiture_id" => $body->voiture_id,
        "parking_id" => $body->parking_id,
        "debut_disponibilite" => $body->debut_disponibilite,
        "fin_disponibilite" => $body->fin_disponibilite,
        "prix" => $body->prix
    );
    $response = $sql->execute($var);
    header(status_code_header(201));
    echo json_encode(array(
        "success" => true
    ));
} elseif ($_SERVER['REQUEST_METHOD'] == "DELETE" && isset($_GET['id'])) {
    // Permet de supprimer une ressource
    $sqlDelete = $conn->prepare('DELETE FROM vehicule WHERE id = :id');
    $array = array(
        'id' => $_GET['id']
    );
    $sqlDelete->execute($array);
    // Toujours mettre un header
    header(status_code_header(200));
    // Si aucune ressource trouvée on renvoie un array vide
    echo json_encode(array(
        "success" => true
    ));
} elseif ($_SERVER['REQUEST_METHOD'] == "PUT" && isset($_GET['id'])) {
    // Récupère le body de la requête POST
    $body = json_decode(file_get_contents("php://input"));
    $sqlDelete = $conn->prepare('UPDATE vehicule SET couleur = :couleur WHERE id = :id');
    $array = array(
        'id' => $_GET['id'],
        'couleur' => $body->couleur
    );
    $sqlDelete->execute($array);
    // Toujours mettre un header
    header(status_code_header(200));
    // Si aucune ressource trouvée on renvoie un array vide
    echo json_encode(array(
        "success" => true
    ));
} else {
    // Retourne mauvaise requête si aucune des méthodes précédentes
    header(status_code_header(404));
}

