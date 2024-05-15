<!DOCTYPE html>
<html lang="fr">
<head>
    <title> Renault </title>
    <meta charset= 'utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<link rel="stylesheet" href="../../CSS/Voitures.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>  
        <p><h1> Renault </h1></p>
        
        <?php include("menu-collection.php"); ?>
        <br>
        
    <form action="Vehicule-ancien-Renault.php" method="GET">
        <label for="search">Rechercher un véhicule :</label>
        <input type="text" id="search" name="search">
        <button type="submit">Rechercher</button>
    </form>
	  
	 <form action="Vehicule-ancien-Renault.php" method="GET" class="sort-buttons-container">
    	<button class="sort-button" type="submit" name="tri" value="prix_asc">Trier par prix croissant</button>
    	<button class="sort-button" type="submit" name="tri" value="prix_desc">Trier par prix décroissant</button>
    	<button class="sort-button" type="submit" name="tri" value="chevaux_asc">Trier par chevaux croissant</button>
    	<button class="sort-button" type="submit" name="tri" value="chevaux_desc">Trier par chevaux décroissant</button>
	</form>

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
        WHERE (Marque LIKE '%$searchTerm%' OR Nom_du_model LIKE '%$searchTerm%')
        AND Type_de_Vehicule = 'Collection'
		AND Marque = 'Renault' AND Marque = 'renault' AND Marque = 'Alpine' AND Marque = 'alpine'";

		// Si un tri par prix ou par cheveaux est demandé
		if ($tri === 'prix_asc') {
    		$sql .= " ORDER BY voitures.Prix ASC";
		} elseif ($tri === 'prix_desc') {
    		$sql .= " ORDER BY voitures.Prix DESC";
		} elseif ($tri === 'chevaux_asc') {
    		$sql .= " ORDER BY voitures.Chevaux ASC";
		} elseif ($tri === 'chevaux_desc') {
    		$sql .= " ORDER BY voitures.Chevaux DESC";
		}

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
			  	echo "<img src='../../uploads/" . $row["photo1"] . "' alt='Photo 1'>";
			  	echo "<img src='../../uploads/" . $row["photo2"] . "' alt='Photo 2'>";
			  	echo "<img src='../../uploads/" . $row["photo3"] . "' alt='Photo 3'>";
			  	echo "<p><strong><a href='../../details.php?id=".$row["id"]."'>En savoir plus : </a></strong></p>"; // Verifie l'id de la voiture pour ouvire la page details correspondant a l'id
              echo "</div>";
            }
        } else {
        echo "Aucun résultat trouvé pour la recherche : '$searchTerm'.";
        }

        // Fermer la connexion
        $connexion->close();
        ?>
    </section>

  <?php include("../../footer.php"); ?>
</body>
</html>