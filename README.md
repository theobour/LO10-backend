# Route de l'api

## API du site

### Aeroport

* GET /api/aeroport.php (AUTH): retourne l'ensemble des aéroports partenaires
* POST /api/aeroport.php : créer un aéroport

### Location

* GET /api/location.php (utilisateur_id) : retourne les locations d'un utilisateur
* POST /api/location.php : créer une location
* GET /api/location.php (aeroport_id, date_debut, date_fin) : retourne les réservations possibles

### Voiture

* GET /api/voiture.php (id) : retourne une voiture
* DELETE /api/voiture.php (id) : supprime une voiture
* GET /api/voiture.php (utilisateur_id) : retourne les voitures d'un utilisateur
* POST /api/voiture.php : pour créer une voiture
  
### Parking

* GET /api/parking.php (id) : retourne un parking
* GET /api/parking.php (aeroport_id) : retourne les parkings d'un aéroport
* GET /api/parking.php : retourne l'ensemble des parkings
* POST /api/parking.php : créer un parking

### Utilisateurs

* POST /api/auth.php : pour se connecter, retourne le profil de l'utilisateur
* GET /api/auth.php (id) : retourne le profil d'un utilisateur
* GET /api/auth.php : retourne l'ensemble des utilisateurs
* PUT /api/auth.php (id) : modifie un utilisateur
* POST /api/signin.php : pour créer un utilisateur

### Réservations

* POST /api/reservation.php : créer une réservation
* GET /api/reservation.php (locataire_id) : retourne les réservations d'un utilisateur
  



## API ouverte pour l'extérieur

* GET /api/services/parking.php : retourne l'ensemble des parkings partenaires
* GET /api/services/location.php (param : aeroport_id, date_debut, date_fin) : retourne l'ensemble des voitures disponibles entre deux dates pour un aéroport donné
* /api/services/aeroport.php : retourne l'ensemble des aéroports partenaires