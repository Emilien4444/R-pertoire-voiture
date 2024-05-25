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

    // Vérifier si les valeurs sont définies
if (isset($_POST['marque'], $_POST['type'], $_POST['modele'], $_POST['annee'], $_POST['moteur'], $_POST['chevaux'], $_POST['prix'], $_POST['description'])) {
    // Récupérer les données du formulaire
    $marque = $_POST['marque'];
    $type = $_POST['type'];
    $modele = $_POST['modele'];
    $annee = $_POST['annee'];
    $moteur = $_POST['moteur'];
    $chevaux = $_POST['chevaux'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];

    // Enregistrer les données dans la base de données
    $sql = "INSERT INTO voitures (Marque, Type_de_Vehicule, Nom_du_model, Annee, Moteur, Chevaux, Prix, Description, Valide) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)";
    $requete = $connexion->prepare($sql);
    $requete->bind_param("ssssssss", $marque, $type, $modele, $annee, $moteur, $chevaux, $prix, $description);
    $requete->execute();

    // Vérifier si la requête d'insertion a réussi
    if ($requete->affected_rows > 0) {
        // Récupérer l'ID de la voiture ajoutée
        $id_voiture = $requete->insert_id;

        // Enregistrer les photos dans un dossier sur le serveur
        $targetDir = "uploads/";

        // Déplacer les fichiers téléchargés vers le dossier cible
	$photo1 = basename($_FILES["photo1"]["name"]); //récupère le nom du fichier téléchargé, "basename()" est utilisée pour extraire le nom de fichier
        $photo2 = basename($_FILES["photo2"]["name"]);
        $photo3 = basename($_FILES["photo3"]["name"]);

	  	move_uploaded_file($_FILES["photo1"]["tmp_name"], $targetDir . $photo1); // déplace le fichier temporaire téléchargé pour la première image vers le dossier cible spécifié par $targetDir
        move_uploaded_file($_FILES["photo2"]["tmp_name"], $targetDir . $photo2);
        move_uploaded_file($_FILES["photo3"]["tmp_name"], $targetDir . $photo3);

        // Mettre à jour les chemins des photos dans la base de données
        $sql_photos = "UPDATE voitures SET photo1 = ?, photo2 = ?, photo3 = ? WHERE id = ?";
        $requete_photos = $connexion->prepare($sql_photos);
        $requete_photos->bind_param("sssi", $photo1, $photo2, $photo3, $id_voiture);
        $requete_photos->execute();

        // Vérifier si la requête de mise à jour a réussi
        if ($requete_photos->affected_rows > 0) {
            // Rediriger vers la page des voitures de la communauté là ou il y à toutes les voitures
            header("Location: vehicule-communaute.php?success=1");
            exit();
        } else {
            echo "Erreur lors de la mise à jour des chemins des photos dans la base de données.";
        }
    } else {
        echo "Erreur lors de l'insertion des données dans la base de données.";
    }

    // Fermer les requêtes et la connexion
    $requete->close();
    $requete_photos->close();
    $connexion->close();
} else {
    echo "Tous les champs du formulaire ne sont pas définis.";
}

?>
