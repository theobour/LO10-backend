<?php
require('../config/header.php');
require('../config/conn.php');

// Pour retourner du JSON partout
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['aeroport_id']) && isset($_GET['date_debut']) && isset($_GET['date_fin'])) {
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
} else {
    // Retourne mauvaise requête si aucune des méthodes précédentes
    header(status_code_header(404));
}
?>
