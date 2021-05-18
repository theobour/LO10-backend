<?php
require('../config/header.php');
require('../config/conn.php');

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

} else {
    // Retourne mauvaise requête si aucune des méthodes précédentes
    header(status_code_header(404));
}
?>