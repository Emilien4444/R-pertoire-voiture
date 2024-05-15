<!DOCTYPE html>
<html>
<head>
    <title>Véhicules Sportifs </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<link rel="stylesheet" href="../../CSS/Voitures.css">
    <script type="text/javascript"src="JavaScript-collection-sportif.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>
    <h1>Véhicules Sportifs</h1>

    <nav> 
    <ul>
	  	<li><a href="../../accueil.php"> Accueil </a></li>
	  	<li><a href="Vehicule-sportifs-AlfaRomeo.php">Alfa Romeo </a></li>
        <li><a href="Vehicule-sportifs-AstonMartin.php">Aston Martin </a></li>
        <li><a href="Vehicule-sportifs-Audi.php">Audi </a></li>
        <li><a href="Vehicule-sportifs-Bentley.php">Bentley </a></li>
	  	<li><a href="Vehicule-sportifs-BMW.php">BMW </a></li>
        <li><a href="Vehicule-sportifs-Bugatti.php">Bugatti </a></li>
        <li><a href="Vehicule-sportifs-Ferrari.php">Ferrari </a></li>
        <li><a href="Vehicule-sportifs-Ford.php">Ford </a></li>
        <li><a href="Vehicule-sportifs-Jaguard.php">Jaguard </a></li>
        <li><a href="Vehicule-sportifs-Koenigsegg.php">Koenigsegg </a></li>
        <li><a href="Vehicule-sportifs-Lamborghini.php">Lamborghini </a></li>
        <li><a href="Vehicule-sportifs-Maserati.php">Maserati </a></li>
        <li><a href="Vehicule-sportifs-Mclaren.php">Mclaren </a></li>
        <li><a href="Vehicule-sportifs-Mercedes.php">Mercedes-Benz </a></li>
        <li><a href="Vehicule-sportifs-Pagani.php">Pagani </a></li>
        <li><a href="Vehicule-sportifs-Porsche.php">Porsche </a></li>
        <li><a href="Vehicule-sportifs-Renault.php">Renault </a></li>
        <li><a href="Vehicule-sportifs-RollsRoyce.php">Rolls Royce </a></li>
    </ul>
	</nav>
    <br><br><br><br>

    <form action="Vehicule-sportif.php" method="GET">
        <label for="search">Rechercher un véhicule :</label>
        <input type="text" id="search" name="search">
        <button type="submit">Rechercher</button>
    </form>
  
  	<form action="Vehicule-sportif.php" method="GET" class="sort-buttons-container">
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
		AND Type_de_Vehicule = 'sportive'";

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
        echo "Aucun résultat trouvé pour la recherche : '$searchTerm' dans la catégorie collection.";
        }

        // Fermer la connexion
        $connexion->close();
        ?>
    </section>



  <?php include("../../footer.php"); ?>
	
</body>
</html>
