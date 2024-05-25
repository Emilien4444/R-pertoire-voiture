<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <title>Véhicules d'exception</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/communaute.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>

  <p><h1> Vos Favoris </h1></p>

<nav>
    <ul>
        <li><a href="accueil-securise.php">Accueil</a></li>
        <li><a href="Vehicule-collection\collection-Secu\Vehicule-collection-securise.php">Véhicules de collections</a></li>
	  	<li><a href="Vehicule-sportif\sportifs-Secu\Vehicule-sportif-securise.php">Véhicules sportifs</a></li>
        <li><a href="Vehicule-atypique\Atypique-Secu\Vehicule-atypique-securise.php">Véhicules atypiques</a></li>
        <li><a href="Moncompte.php">Mon compte</a></li>
        <li><a href="vehicule-communaute.php">Véhicule de la communauté</a></li>
    </ul>
</nav>
<br><br><br>

<form action="favoris.php" method="GET">
    <label for="search">Rechercher un véhicule :</label>
    <input type="text" id="search" name="search">
    <button type="submit">Rechercher</button>
</form>

<form action="favoris.php" method="GET" class="sort-buttons-container">
    <button class="sort-button" type="submit" name="tri" value="prix_asc">Trier par prix croissant</button>
    <button class="sort-button" type="submit" name="tri" value="prix_desc">Trier par prix décroissant</button>
    <button class="sort-button" type="submit" name="tri" value="chevaux_asc">Trier par chevaux croissant</button>
    <button class="sort-button" type="submit" name="tri" value="chevaux_desc">Trier par chevaux décroissant</button>
</form>
  
<section class="vehicule-container">
    <?php 
    session_start(); // Démarre la session

    // Vérifie si l'utilisateur est connecté
    if(!isset($_SESSION['id'])) {
        // Redirection vers la page de connexion si l'utilisateur n'est pas connecté ( par sécurité car impossible d'aller sur cette page sans être connécté)
        header("Location: accueil.php");
        exit(); // Arrête l'exécution du script 
    }

    // Récupérer l'identifiant de l'utilisateur connecté
    $id_utilisateur = $_SESSION['id'];

    /// Connexion à la base de données
    $serveur = "we96pm.myd.infomaniak.com";
    $utilisateur = "we96pm_system";
    $motDePasse = "2107Emilienb";
    $baseDeDonnees = "we96pm_projet";

    $connexion = new mysqli($serveur, $utilisateur, $motDePasse, $baseDeDonnees);

    // Vérifie la connexion
    if ($connexion->connect_error) {
        die("La connexion à la base de données a échoué : " . $connexion->connect_error); // gestion d'erreur
    }

	// Récupérer le paramètre de tri (si il existe)
        $tri = isset($_GET['tri']) ? $_GET['tri'] : '';

    // Requête SQL pour sélectionner les véhicules favoris de l'utilisateur connecté
    $sql = "SELECT voitures.* FROM voitures JOIN favoris
	ON voitures.id = favoris.id_voiture WHERE favoris.id_utilisateur = ?";

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

    
    // Préparation de la requête
    $stmt = $connexion->prepare($sql);

    // Vérification que la préparation soit bien éfféctué
    if ($stmt) {
        // Liez le paramètre à la valeur réelle
        $stmt->bind_param("i", $id_utilisateur);
        $stmt->execute();

        // Récupération des résultats
        $result = $stmt->get_result();

        // Affichage des résultats de la requête
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='vehicule'>";
                echo "<h2><strong>" . $row["Marque"] . "</strong><strong> " . $row["Nom_du_model"] . "</strong><strong> (" . $row["Annee"] . "</strong>)</h2>";
                echo "<p><strong>Type de véhicule:</strong> " . $row["Type_de_Vehicule"] . "</p>";
                echo "<p><strong>Moteur:</strong> " . $row["Moteur"] . "</p>";
                echo "<p><strong>Chevaux:</strong> " . $row["Chevaux"] . "</p>";
                echo "<p><strong>Prix:</strong> " . $row["Prix"] . "</p>";
                echo "<img src='uploads/" . $row["photo1"] . "' alt='Photo 1'>";
                echo "<img src='uploads/" . $row["photo2"] . "' alt='Photo 2'>";
                echo "<img src='uploads/" . $row["photo3"] . "' alt='Photo 3'>";
                echo "<p><strong><a href='details-Secu.php?id=".$row["id"]."'>En savoir plus : </a></strong></p>"; // Vérifie l'id de la voiture pour ouvrir la page détails correspondant à l'id

                // Formulaire pour supprimer des favoris
			  	echo "<div class='action-buttons-container'>";
                			echo "<form action='supprimer_des_favoris.php' method='POST'>"; 
                				echo "<input type='hidden' name='id_voiture' value='" . $row["id"] . "'>";
                				echo "<button type='submit' name='supprimer_des_favoris'>Supprimer des Favoris</button>"; 
                			echo "</form>";
				echo "</div>";
             echo "</div>";
            }
        } else {
            echo "Aucun véhicule favori trouvé.";
        }

        // On ferme de la requête 
        $stmt->close();
    } else {
        // Gestion des erreurs si la préparation de la requête a échoué
        echo "Erreur lors de la préparation de la requête.";
    }

    // Fermeture de la connexion
    $connexion->close();
?>
 </section>

<?php include("footer.php"); ?>

<!-- Script JavaScript pour afficher un message lorsqu'un véhicule est supprimé des favoris -->
<script>
    const urlParams = new URLSearchParams(window.location.search);
    const deletionSuccess = urlParams.get('deletion_success');
    if (deletionSuccess === '1') {
        alert("Le véhicule a été supprimé des favoris avec succès !");
    }
</script>


</body>
</html>
