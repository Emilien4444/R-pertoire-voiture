<?php
session_start(); // Démarre la session

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: accueil.php");
    exit();
}

// Vérifie si le formulaire de suppression a été soumis
if (isset($_POST['supprimer_des_favoris'])) {
    // Récupére l'identifiant de l'utilisateur connecté
    $id_utilisateur = $_SESSION['id'];

    // Récupére l'identifiant du véhicule à supprimer des favoris
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

    // Requête SQL pour supprimer le véhicule des favoris de l'utilisateur connecté
    $sql = "DELETE FROM favoris WHERE id_utilisateur = ? AND id_voiture = ?";

    // Prépare la requête
    $stmt = $connexion->prepare($sql);

    // Si la préparation de la requête a réussi alors
    if ($stmt) {
        // Liez les paramètres et exécutez la requête
        $stmt->bind_param("ii", $id_utilisateur, $id_voiture);
        $stmt->execute();

        // Redirection vers la page favoris.php après la suppression
        header("Location: favoris.php?deletion_success=1");
        exit(); 
    } else {
        // Gestion des erreurs si la préparation de la requête a échoué
        echo "Erreur lors de la préparation de la requête de suppression.";
    }

    // Fermer la connexion
    $connexion->close();
} else {
    // Redirection vers la page favoris.php si le formulaire n'a pas été soumis correctement
    header("Location: favoris.php?deletion_success=0");
    exit(); // Arrêter l'exécution du script après la redirection
}
?>
