# Structure de fichier

## Dossier config

### conn.php

Contient les informations pour se connecter à la base de donnée.

### header.php

Contient l'ensemble des status possibles pour le code de réponse. Il contient la fonction status_code_header qui prend en entrée un status code et retourne le header bien formaté.

## Dossier API

Le dossier API contient une page example.php qui est un template pour les autres pages. Dans cette exemple sont présents 5 routes :
* Trouver un élément particulier avec id=X -> nécessite ?id=
* Trouver l'ensemble des éléments
* Créer un élément -> accepte un JSON
* Modifier un élément -> accepte un JSON et nécessite ?id=
* Supprimer un élément -> nécessite ?id=

Pour les requêtes sur la base de donnée nous utilisons PDO.

Il faudra faire une page par élément que nous souhaitons traiter. Ex : pour récupérer, créer, modifier, ... les véhicules il faudra créer vehicule.php. Pour garder en cohéerence intérogera la même base de donnée sur chaque page (pas comme sur l'exemple où pour chaque méthode une autre table est requêtée)

Avant de retourner une réponse on assigne toujours un header à la réponse :
```
header(status_code_header(STATUS_CODE));
```