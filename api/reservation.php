<?php
require('../config/header.php');
require('../config/conn.php');
require('auth_check.php');
// Pour retourner du JSON partout
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['locataire_id'])) {
    // Permet de récupérer l'ensemble des réservations d'un utilisateur locataire
    $sql = $conn->prepare('SELECT r.*, v.*,p.*,a.*, r.prix as prix FROM reservation as r, voiture as v, parking as p, aeroport as a WHERE r.locataire_id = :id AND r.parking_id = p.id AND p.aeroport_id = a.id AND r.voiture_id = v.id');
    $var = array(
        'id' => $_GET['locataire_id']
    );
    $sql->execute($var);
    $data = $sql->fetchAll(PDO::FETCH_ASSOC);
    // Toujours mettre un header
    header(status_code_header($data ? 200 : 404));
    // Si aucune ressource trouvée on renvoie un json vide
    echo $data ? json_encode($data) : '{}';

} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {

// Permet de créer une réservation
    $auth = check_auth(apache_request_headers(), $conn);
    if ($auth === true) {
        $body = json_decode(file_get_contents("php://input"));
        $sql = $conn->prepare('INSERT INTO reservation (voiture_id,locataire_id,parking_id,date_debut,date_fin,prix) VALUES (:voiture_id,:locataire_id,:parking_id,:date_debut,:date_fin, :prix)');
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
    } else {
        header(status_code_header(401));
        return;
    }
} else {
    // Retourne mauvaise requête si aucune des méthodes précédentes
    header(status_code_header(404));
}
?>
