<?php
require('../config/header.php');
require('../config/conn.php');

// Pour retourner du JSON partout
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == "GET" && $_GET['id']) {
    // Permet de trouver une ressource avec son id
    try {
        $sql = $conn->prepare('SELECT * FROM vehicule WHERE id = :id');
        $var = array(
            'id' => $_GET['id']
        );
        $sql->execute($var);
        $data = $sql->fetch(PDO::FETCH_ASSOC);
        // Toujours mettre un header
        header(status_code_header(201));
        // Si aucune ressource trouvée on renvoie un json vide
        echo $data ? json_encode($data) : '{}';
    } catch (exception $err) {
        header(status_code_header(500));
        echo 'Error ' . $err;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
    // Permet de trouver l'ensemble des ressources
    try {
        $sql = $conn->prepare('SELECT * FROM vehicule');
        $sql->execute();
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        // Toujours mettre un header
        header(status_code_header(200));
        // Si aucune ressource trouvée on renvoie un array vide
        echo $data ? json_encode($data) : '[]';
    } catch (exception $err) {
        header(status_code_header(500));
        echo 'Error ' . $err;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
// Permet de créer une ressource
} elseif ($_SERVER['REQUEST_METHOD'] == "DELETE") {
// Permet de supprimer une ressource
} elseif ($_SERVER['REQUEST_METHOD'] == "PUT" && $_GET['id']) {

} else {
    // Retourne mauvaise requête si aucune des méthodes précédentes
    header(status_code_header(400));
}
?>
