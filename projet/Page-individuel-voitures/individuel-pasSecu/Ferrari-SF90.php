<!DOCTYPE html>
    <html>
        <head>
            <title> Ferrari-SF90 Stradale </title>
            <meta charset= 'utf-8'>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
		  <link rel="stylesheet" href="../../CSS/CSS-individuel.css">
            <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
        </head>
        <body>
        <?php include("../../header.php"); ?>
        <br>
	  
	  	<nav>
    		<ul>
			  <li><a href="../../accueil.php"> Accueil</a></li>
			  <li><a href="../../Vehicule-collection\collection-pasSecu\Vehicule-collection.php">Véhicules de collections</a></li>
			  <li><a href="../../Vehicule-sportif\sportifs-pasSecu\Vehicule-sportif.php">Véhicules sportifs</a></li>
			  <li><a href="../../Vehicule-atypique\Atypique-pasSecu\Vehicule-atypique.php">Véhicules atypiques</a></li>
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

        // Récupérer le terme de recherche (s'il existe)
			$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

		// Récupérer le paramètre de tri (s'il existe)
		$tri = isset($_GET['tri']) ? $_GET['tri'] : '';
    
        // Construire la requête SQL en fonction du terme de recherche
        $sql = "SELECT * FROM voitures 
		WHERE Type_de_Vehicule = 'sportive' 
		AND Marque = 'Ferrari' AND Nom_du_model = 'SF90 Stradale ' ";

        $result = $connexion->query($sql);
    
        // Afficher les résultats de la recherche
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
              echo "<div class='vehicule'>";
                echo "<h2><strong>" . $row["Marque"] . "</strong><strong> " . $row["Nom_du_model"] . "</strong> ( <strong>" . $row["Annee"] . "</strong>)</h2>";
                echo "<p><strong> Type de véhicule </strong> : " . $row["Type_de_Vehicule"] . "</p>";
                echo "<p> <strong> Moteur</strong>: " . $row["Moteur"] . "</p>";
                echo "<p> <strong> Chevaux</strong>: " . $row["Chevaux"] . "</p>";
                echo "<p><strong> Prix</strong>: " . $row["Prix"] . "</p>";
                echo "<p><strong>Description</strong>: " . $row["Description"] . "</p>";
			  	echo "<img src='../../uploads/" . $row["photo1"] . "' alt='Photo 1'>";
			  	echo "<img src='../../uploads/" . $row["photo2"] . "' alt='Photo 2'>";
			  	echo "<img src='../../uploads/" . $row["photo3"] . "' alt='Photo 3'>";
              echo "</div>";
            }
        }

        // Fermer la connexion
        $connexion->close();
        ?>
    </section>
        
		  <?php include("../../footer.php"); ?>
</body>
</html>