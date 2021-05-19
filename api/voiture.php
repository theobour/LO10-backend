<?php
require('../config/header.php');
require('../config/conn.php');

// Pour retourner du JSON partout
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])) {
    // Permet de trouver une ressource avec son id

    $sql = $conn->prepare('SELECT * FROM voiture WHERE id = :id');
    $var = array(
        'id' => $_GET['id']
    );
    $sql->execute($var);
    $data = $sql->fetch(PDO::FETCH_ASSOC);
    // Toujours mettre un header
    header(status_code_header($data ? 200 : 404));
    // Si aucune ressource trouvée on renvoie un json vide
    echo $data ? json_encode($data) : '{}';

} elseif ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['utilisateur_id'])) {
    // Permet de récupérer les voitures d'un utilisateur

    $sql = $conn->prepare('SELECT * FROM voiture WHERE proprietaire_id = :id');
    $sql->execute(array(
        'id'=>$_GET['utilisateur_id']
    ));
    $data = $sql->fetchAll(PDO::FETCH_ASSOC);
    // Toujours mettre un header
    header(status_code_header($data ? 200 : 404));
    // Si aucune ressource trouvée on renvoie un array vide
    echo $data ? json_encode($data) : '[]';
}
elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
    // Permet de trouver l'ensemble des ressources

    $sql = $conn->prepare('SELECT * FROM vehicule');
    $sql->execute();
    $data = $sql->fetchAll(PDO::FETCH_ASSOC);
    // Toujours mettre un header
    header(status_code_header($data ? 200 : 404));
    // Si aucune ressource trouvée on renvoie un array vide
    echo $data ? json_encode($data) : '[]';
}
elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
// Permet de créer une ressource
    $body = json_decode(file_get_contents("php://input"));
    $sql = $conn->prepare('INSERT INTO voiture (proprietaire_id,couleur,marque,nb_place,etat,type) VALUES (:proprietaire_id,:couleur,:marque,:nb_place,:etat,:type)');
    $var = array(
        "type"=>$body->type,
        "couleur"=>$body->couleur,
        "marque"=>$body->marque,
        "nb_place"=>$body->nb_place,
        "etat"=>$body->etat,
        "proprietaire_id"=>$body->proprietaire_id
    );

    $sql->execute($var);
    header(status_code_header(201));
    echo json_encode(array(
        "success" => true
    ));
}
elseif ($_SERVER['REQUEST_METHOD'] == "DELETE" && isset($_GET['id'])) {
    // Permet de supprimer une voiture
    // key_check =0 pour éviter de check les foreign key
    $sqlDelete = $conn->prepare('DELETE FROM voiture WHERE id = :id');
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
}
else {
    // Retourne mauvaise requête si aucune des méthodes précédentes
    header(status_code_header(404));
}
?>
