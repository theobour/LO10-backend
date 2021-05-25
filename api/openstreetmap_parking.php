<?php
require("parkingAPI.php");
$tab_localisation = get_localisation();
?>
<!DOCTYPE html> 
<html>
    <head>
        <meta charset="utf-8">
        <!-- Nous chargeons les fichiers CDN de Leaflet. Le CSS AVANT le JS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin="" />
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css" />
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css" />
        <style type="text/css">
	    #map{ /* la carte DOIT avoir une hauteur sinon elle n'apparaît pas */
	        height:300px;
	    }
	</style>
	<title>Carte</title>
    </head>
    <body>
	<div id="map">
	    <!-- Ici s'affichera la carte -->
	</div>
	
        <!-- Fichiers Javascript -->
        <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>
        <script type='text/javascript' src='https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js'></script>
	<script type="text/javascript">

	    // On initialise la latitude et la longitude de Paris (centre de la carte)
	    var lat = 48.852969;
	    var lon = 2.349903;
	    var macarte = null;
            var markerClusters; // Servira à stocker les groupes de marqueurs
            // Nous initialisons une liste de marqueurs
			
			
            var parkings_loc = <?php echo json_encode($tab_localisation)?>;
			
	    // Fonction d'initialisation de la carte
            function initMap() {
		// Créer l'objet "macarte" et l'insèrer dans l'élément HTML qui a l'ID "map"
                macarte = L.map('map').setView([lat, lon], 10);
                markerClusters = L.markerClusterGroup(); // Nous initialisons les groupes de marqueurs
                // Leaflet ne récupère pas les cartes (tiles) sur un serveur par défaut. Nous devons lui préciser où nous souhaitons les récupérer. Ici, openstreetmap.fr
                L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
                    // Il est toujours bien de laisser le lien vers la source des données
                    attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
                    minZoom: 1,
                    maxZoom: 20
                }).addTo(macarte);4
				
				
                // Nous parcourons la liste des villes
                for (p in parkings_loc) {
                    var marker = L.marker([parkings_loc[p].Lat, parkings_loc[p].Long]); // pas de addTo(macarte), l'affichage sera géré par la bibliothèque des clusters
					marker.bindPopup(
						'<ul><h3>'+parkings_loc[p].nom+'</h3>'+
						'<a href ="'+parkings_loc[p].url+'">'+parkings_loc[p].adresse+'</a>'+
						'<li>1h : '+parkings_loc[p].Prix1H+'€</li>'+
						'<li>2h : '+parkings_loc[p].Prix2H+'€</li>'+
						'<li>3h : '+parkings_loc[p].Prix3H+'€</li>'+
						'<li>4h : '+parkings_loc[p].Prix4H+'€</li>'+
						'<li>24h : '+parkings_loc[p].Prix24H+'€</li></ul>'+
						'<p>Nombre de places : '+parkings_loc[p].nbPlace+'</p>');
                    markerClusters.addLayer(marker); // Nous ajoutons le marqueur aux groupes
                }
                macarte.addLayer(markerClusters);
            }
	    window.onload = function(){
		// Fonction d'initialisation qui s'exécute lorsque le DOM est chargé
		initMap(); 
	    };
	</script>
    </body>
</html>