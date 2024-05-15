<!-- ajouter le véhicule aux favoris dans la base de données-->

<?php
// Vérifie si le formulaire d'ajout aux favoris a été soumis
if(isset($_POST['ajouter_aux_favoris'])) {
    // Récupère les données du formulaire
    $id_utilisateur = $_POST['id_utilisateur'];
    $id_voiture = $_POST['id_voiture'];
    
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

    // Requête SQL pour ajouter le véhicule aux favoris de l'utilisateur dans la table "favoris"
    $sql = "INSERT INTO favoris (id_utilisateur, id_voiture) VALUES (?, ?)";
    
    // Préparation de la requête
    $stmt = $connexion->prepare($sql);

    // Vérifie si la préparation de la requête a réussi
    if ($stmt) {
      // Liez les paramètres et exécutez la requête
	  $stmt->bind_param("ii", $id_utilisateur, $id_voiture); // assure que les valeurs des variables $id_utilisateur et $id_voiture sont correctement liées à la requête SQL, garantissant la sécurité des données.
      $stmt->execute();

      // Redirection vers la page vehicule-communaute.php avec un paramètre de succès
      header("Location: favoris.php?success=1");
      exit();
    } else {
      // Gestion des erreurs si la préparation de la requête a échoué
      echo "Erreur lors de la préparation de la requête.";
    }

    // Fermer la connexion
    $connexion->close();
}
?>
