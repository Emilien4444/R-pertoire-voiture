<!DOCTYPE html>
		<html lang="fr-FR">
		<head>
		  	<title>Détails du véhicule</title>
    		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		  	<link rel="stylesheet" href="CSS/CSS-individuel.css">
            <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
        </head>
        <body>
		  
        <?php include("header.php"); ?>
        <br><br>
		  
		<nav>
    	<ul>
        	<li><a href="accueil-securise.php"> Accueil</a></li>
        	<li><a href="Vehicule-collection\collection-Secu\Vehicule-collection-securise.php">Véhicules de collections</a></li>
        	<li><a href="Vehicule-sportif\sportifs-Secu\Vehicule-sportif-securise.php">Véhicules sportifs</a></li>
        	<li><a href="Vehicule-atypique\Atypique-Secu\Vehicule-atypique-securise.php">Véhicules atypiques</a></li>
        	<li><a href="Moncompte.php"> Mon compte </a></li>
        	<li><a href="vehicule-communaute.php"> Véhicule de la communauté </a></li>
   			<li><a href='favoris.php'> Vos favoris </a></li>
    	</ul>
		</nav>
		<br><br><br>

    	<section class="vehicule-container">
        	<?php
        	// Connexion à la base de données
        	$serveur = "we96pm.myd.infomaniak.com";
			$utilisateur = "we96pm_system";
			$motDePasse = "2107Emilienb";
			$baseDeDonnees = "we96pm_projet";

        	$connexion = new mysqli($serveur, $utilisateur, $motDePasse, $baseDeDonnees);

        	// Vérifie la connexion
        	if ($connexion->connect_error) {
            	die("La connexion à la base de données a échoué : " . $connexion->connect_error); // gestion d'erreur
        	}    


        	// Obtenir l'identifiant du véhicule depuis la base de donnée
			$idVehicule = isset($_GET['id']) ? intval($_GET['id']) : 0;

			// Requête SQL pour récupérer les informations du véhicule
			$sql = "SELECT * FROM voitures WHERE id = $idVehicule";
			$resultat = mysqli_query($connexion, $sql);

			// Vérifier si le véhicule existe
			if (mysqli_num_rows($resultat) > 0) {
    			$row = mysqli_fetch_assoc($resultat);
			} else {
    			header('Location: vehicule-communaute.php?erreur=vehicule_introuvable'); // Redirection vers la page "vehicule-communaute.php" en cas de véhicule introuvable
    		exit;
			}
		
			// Afficher les résultats de la recherche
			echo "<div class='vehicule'>";
        		echo "<h2><strong>" . $row["Marque"] . "</strong><strong> " . $row["Nom_du_model"] . "</strong> ( <strong>" . $row["Annee"] . "</strong>)</h2>";
        		echo "<p><strong> Type de véhicule </strong> : " . $row["Type_de_Vehicule"] . "</p>";
        		echo "<p> <strong> Moteur</strong>: " . $row["Moteur"] . "</p>";
        		echo "<p> <strong> Chevaux</strong>: " . $row["Chevaux"] . "</p>";
        		echo "<p><strong> Prix</strong>: " . $row["Prix"] . "</p>";
        		echo "<p><strong>Description</strong>: " . $row["Description"] . "</p>";
        		echo "<img src='../uploads/" . $row["photo1"] . "' alt='Photo 1'>";
        		echo "<img src='../uploads/" . $row["photo2"] . "' alt='Photo 2'>";
        		echo "<img src='../uploads/" . $row["photo3"] . "' alt='Photo 3'>";
			echo "</div>";
        	// Fermer la connexion
        	$connexion->close();
        	?>
    	</section>
        
    	<?php include("footer.php"); ?>
</body>
</html>






