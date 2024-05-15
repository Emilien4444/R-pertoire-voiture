<!DOCTYPE html>
<html lang="fr-FR">
    <head>
        <title> Véhicules d'exception </title>
        <meta charset= 'utf-8'>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="CSS/communaute.css">
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    </head>
    <body>
	  <?php include("header.php"); ?>
	  
	  
	  <nav>
        <ul>
            <li><a href="accueil-securise.php"> Accueil</a></li>
            <li><a href="Vehicule-collection\collection-Secu\Vehicule-collection-securise.php" >Véhicules de collections</a></li>
            <li><a href="Vehicule-sportif\sportifs-Secu\Vehicule-sportif-securise.php" >Véhicules sportifs</a></li>
            <li><a href="Vehicule-atypique\Atypique-Secu\Vehicule-atypique-securise.php" >Véhicules atypiques</a></li>
            <li><a href="Moncompte.php"> Mon compte </a></li>
            <li><a href="vehicule-communaute.php"> Véhicule de la communauté </a></li>
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

		// Vérifier si un formulaire a été soumis
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
    		// Vérifier si l'ID de la voiture à valider a été envoyé
    		if (isset($_POST['id_voiture'])) {
        		$id_voiture = $_POST['id_voiture'];

        	// Vérifier quel bouton a été cliqué
            if (isset($_POST['valider'])) {
               // Mettre à jour la colonne "Valide" de la voiture à 1
               $sql = "UPDATE voitures SET Valide = 1 WHERE id = ?";
               $requete = $connexion->prepare($sql);
               $requete->bind_param("i", $id_voiture);
               $requete->execute();

               // Vérifier si la mise à jour a réussi
               if ($requete->affected_rows > 0) {
                    echo "La voiture a été validée avec succès.";
               } else {
                    echo "Erreur lors de la validation de la voiture.";
                }

               // Fermer la requête
               $requete->close();
                } elseif (isset($_POST['refuser'])) {
                    echo "La voiture a été refusée.";
                    // Pas de mise à jour nécessaire pour le refus
                } else {
                    echo "Action non spécifiée.";
                }
            } 
        }

		// Récupérer toutes les voitures non validées
        $sql = "SELECT * FROM voitures WHERE Valide = 0";
        $result = $connexion->query($sql);

        if ($result->num_rows > 0) {
            echo "<h1>Voitures en attente de validation</h1>";
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
               		echo "<p><strong><a href='details-Secu.php?id=".$row["id"]."'>En savoir plus</a></strong></p>"; // Verifie l'id de la voiture pour ouvire la page details correspondant a l'id
                
			  		echo "<div class='action-buttons-container'>";
                        echo "<form action='admin.php' method='post'>";
                            echo "<input type='hidden' name='id_voiture' value='" . $row["id"] . "'>";
                            echo "<button type='submit' name='valider'>Valider</button>"; // Bouton Valider
                            echo "<button type='submit' name='refuser'>Refuser</button>"; // Bouton Refuser
                        echo "</form>";
                    echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>Aucune voiture en attente de validation.</p>";
        }

        // Fermer la connexion
        $connexion->close();
        ?>
    </section>
</body>
</html>
