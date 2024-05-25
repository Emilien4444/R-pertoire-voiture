<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte</title>
    <link rel="stylesheet" href="CSS/MonCompte.css">
</head>
<body>

<?php
	session_start();

	// Connexion à la base de données	
	$serveur = "we96pm.myd.infomaniak.com";
	$utilisateur = "we96pm_system";
	$motDePasse = "2107Emilienb";
	$baseDeDonnees = "we96pm_projet";

	$connexion = new mysqli($serveur, $utilisateur, $motDePasse, $baseDeDonnees);

	if ($connexion->connect_error) {
    	die("La connexion a échoué : " . $connexion->connect_error);
	}

	$id_utilisateur = $_SESSION["id"];

	// Vérification si l'utilisateur est administrateur
	function est_administrateur($connexion, $id_utilisateur) {
	// requête SQL qui compte le nombre de lignes dans la table administrateurs où l'identifiant de l'utilisateur correspond à $id_utilisateur
    	$sql = "SELECT COUNT(*) AS admin_count FROM administrateurs WHERE id = ?"; 
    	$requete = $connexion->prepare($sql);
	  	$requete->bind_param("i", $id_utilisateur); // bind_param utilisée pour lier les valeurs aux paramètres dans la requête SQL
    	$requete->execute();
    	$resultat = $requete->get_result();
    	$row = $resultat->fetch_assoc();
    	$requete->close();
    	return $row['admin_count'] > 0;
	}
	// Vérifier si l'utilisateur est administrateur
	if (est_administrateur($connexion, $id_utilisateur)) {
    	// L'utilisateur est administrateur, afficher le bouton d'accès à la page admin.php
    	echo '<a href="admin.php" class="adminBouton"> Accéder à la page admin </a>';
	} else {
    	// SI l'utilisateur n'est pas administrateur, affiche un message
    	echo "Vous n'avez pas accès à cette fonctionnalité.";
}

// Fonction pour récupérer les informations de l'utilisateur
function info_utilisateur($connexion, $id_utilisateur) {
  $sql = "SELECT nom, prenom, email FROM connexion WHERE id = ?"; // Requête SQl qui va chercher les infos dans la table "connexion"
    $requete = $connexion->prepare($sql);
    $requete->bind_param("i", $id_utilisateur);
    $requete->execute();
    $requete->bind_result($nom, $prenom, $email);
    $requete->fetch();
    $requete->close();
    return array($nom, $prenom, $email);
}

// Fonction pour mettre à jour les informations de l'utilisateur
function mettre_a_jour_infos($connexion, $id_utilisateur, $nouveau_nom, $nouveau_prenom, $nouveau_email) {
    $sql = "UPDATE connexion SET nom = ?, prenom = ?, email = ? WHERE id = ?";
    $requete = $connexion->prepare($sql);
    $requete->bind_param("sssi", $nouveau_nom, $nouveau_prenom, $nouveau_email, $id_utilisateur);
    $resultat = $requete->execute();
    $requete->close();
    return $resultat;
}

// Fonction pour mettre à jour le mot de passe de l'utilisateur
function mettre_a_jour_mot_de_passe($connexion, $id_utilisateur, $nouveau_mot_de_passe) {
    $nouveau_mot_de_passe_hash = password_hash($nouveau_mot_de_passe, PASSWORD_DEFAULT);
    $sql = "UPDATE connexion SET MotDePasse = ? WHERE id = ?";
    $requete = $connexion->prepare($sql);
    $requete->bind_param("si", $nouveau_mot_de_passe_hash, $id_utilisateur); // bind_param utilisée pour lier les valeurs aux paramètres dans la requête SQL
  	$requete->execute(); // Exécute la requête préparé
  	$resultat = $requete->execute(); // Exécute et stocke le resultat
    $requete->close();
    return $resultat;
}

// Vérifier si le formulaire de changement de mot de passe est soumis
if (isset($_POST['changer_mot_de_passe'])) {
    $ancien_mdp = $_POST['ancien_mdp'];
    $nouveau_mdp = $_POST['nouveau_mdp'];
    $confirm_nouveau_mdp = $_POST['confirm_nouveau_mdp'];

    // Vérifier si le mot de passe confirmé correspond au nouveau mot de passe
    if ($nouveau_mdp === $confirm_nouveau_mdp) {
        // Vérifier si l'ancien mot de passe correspond à celui de l'utilisateur
        $sql = "SELECT MotDePasse FROM connexion WHERE id = ?";
        $requete = $connexion->prepare($sql);
        $requete->bind_param("i", $id_utilisateur);
        $requete->execute();
        $requete->bind_result($mot_de_passe_hash);
        $requete->fetch();
        $requete->close();

        if (password_verify($ancien_mdp, $mot_de_passe_hash)) {
            // Mettre à jour le mot de passe
            $resultat = mettre_a_jour_mot_de_passe($connexion, $id_utilisateur, $nouveau_mdp);
            if ($resultat) {
                // Redirection vers la page avec un paramètre de succès
                header("Location: Moncompte.php?success=1");
                exit();
            } else {
                echo "Erreur lors de la mise à jour du mot de passe.";
            }
        } else {
            echo "L'ancien mot de passe est incorrect.";
        }
    } else {
        echo "Le nouveau mot de passe et sa confirmation ne correspondent pas.";
    }
}

// Vérifier si le formulaire de modification des informations est soumis
if (isset($_POST['enregistrer_modifications'])) {
    $nouveau_nom = $_POST['nouveau_nom'];
    $nouveau_prenom = $_POST['nouveau_prenom'];
    $nouveau_email = $_POST['nouveau_email'];

    // Mettre à jour les informations de l'utilisateur
    $resultat = mettre_a_jour_infos($connexion, $id_utilisateur, $nouveau_nom, $nouveau_prenom, $nouveau_email);
    if ($resultat) {
        // Redirection vers la page avec un paramètre de succès
        header("Location: Moncompte.php?success=1");
        exit();
    } else {
        echo "Erreur lors de la mise à jour des informations.";
    }
}

// Récupération des informations de l'utilisateur
list($nom, $prenom, $email) = info_utilisateur($connexion, $id_utilisateur);

$connexion->close(); // ferme la session
?>


<?php include("header.php"); ?>
    <nav>
        <ul>
          <li><a href="accueil-securise.php"> Accueil</a></li>
          <li><a href="Vehicule-collection\collection-Secu\Vehicule-collection-securise.php">Véhicules de collections</a></li>
          <li><a href="Vehicule-sportif\sportifs-Secu\Vehicule-sportif-securise.php" >Véhicules sportifs</a></li>
          <li><a href="Vehicule-atypique\Atypique-Secu\Vehicule-atypique-securise.php">Véhicules atypiques</a></li>
          <li><a href="Moncompte.php"> Mon compte </a></li>
          <li><a href="vehicule-communaute.php"> Véhicule de la communaute  </a></li>
		  <li><a href='favoris.php'> Vos favoris </a></li>;
        </ul>
    </nav>
    <br><br><br>
    
    
    <h1 id="titre">Mon Compte</h1>
    
    <section>
        
        <section>
            <h2>Profil Utilisateur</h2>
            <br><br>
            <p>Nom : <?php echo $nom; ?></p>
            <p>Prénom : <?php echo $prenom; ?></p>
            <p>Email : <?php echo $email; ?></p>
        </section>
        <br><br><br><br><br>
        
        
        <h2> Ajouter une voiture </h2>
        <section class="form-container" >
        <form action="traitement_ajout_voiture.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="marque">Marque :</label>
                <input type="text" id="marque" name="marque" required><br>
            </div>
            
            <div class="form-group">
            <label for="type">Type de véhicule :</label>
            <select id="type" name="type" required>
                <option value="Collection">Collection</option>
                <option value="Sportive">Sportive</option>
                <option value="Atypique">Atypique</option>
            </select><br>
            </div>
            
            <div class="form-group">
                <label for="modele">Modèle :</label>
                <input type="text" id="modele" name="modele" required><br>
            </div>
            
            <div class="form-group">
                <label for="annee">Année :</label>
                <input type="number" id="annee" name="annee" required><br>
            </div>
            
            
            <div class="form-group">
                <label for="moteur">Moteur :</label>
                <input type="text" id="moteur" name="moteur" required><br>
            </div>
            
            <div class="form-group">
            <label for="chevaux">Chevaux :</label>
            <input type="number" id="chevaux" name="chevaux" required><br>
            </div>
            
            <div class="form-group">
                <label for="prix">Prix :</label>
                <input type="number" id="prix" name="prix" required><br>
            </div>
            
            <div class="form-group">
                <label for="description">Description :</label><br>
                <textarea id="description" name="description" rows="4" cols="50" required></textarea><br>
            </div>  
            
            <div class="form-group">
                <label for="photo1">Photo 1 :</label>
                <input type="file" id="photo1" name="photo1" accept="image/*" required><br>

                <label for="photo2">Photo 2 :</label>
                <input type="file" id="photo2" name="photo2" accept="image/*" required><br>

                <label for="photo3">Photo 3 :</label>
                <input type="file" id="photo3" name="photo3" accept="image/*" required><br>
            </div>
            
            <input type="submit" value="Ajouter la Voiture">
        </form>
        </section>
        
        
        <h2> Paramètres </h2>
        
         <section>
            <h2>Modifier les informations</h2>
            <br><br>
		   <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			  <!-- ("$_SERVER["PHP_SELF"]"  utilisé pour obtenir nom fichier PHP en cours d'exécution) envoie les données à la même page PHP qui contient le formulaire lui-même, "htmlspecialchars()" convertit les caractères spéciaux en entités HTML. -->
                <div class="form-group">
                    <label for="nouveau_nom">Nouveau Nom :</label>
                    <input type="text" id="nouveau_nom" name="nouveau_nom" value="<?php echo $nom; ?>" required><br>
                </div>
                
                <div class="form-group">
                    <label for="nouveau_prenom">Nouveau Prénom :</label>
                    <input type="text" id="nouveau_prenom" name="nouveau_prenom" value="<?php echo $prenom; ?>" required><br>
                </div>
                
                <div class="form-group">
                    <label for="nouveau_email">Nouvel Email :</label>
                    <input type="email" id="nouveau_email" name="nouveau_email" value="<?php echo $email; ?>" required><br>
                </div>
                
                
                
                <input type="submit" value="Enregistrer les modifications">
            </form>
        </section>
        <br><br>
        
        
        <section>
            <h2>Modifier le mot de passe</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			  <!-- envoie les données à la même page PHP qui contient le formulaire lui-même, "htmlspecialchars()" convertit les caractères spéciaux en entités HTML. -->
                <div class="form-group">
                    <label for="ancien_mdp">Ancien mot de passe :</label>
                    <input type="password" id="ancien_mdp" name="ancien_mdp" required><br>
                </div>
                
                <div class="form-group">
                    <label for="nouveau_mdp">Nouveau mot de passe :</label>
                    <input type="password" id="nouveau_mdp" name="nouveau_mdp" required><br>
                </div>
                
                <div class="form-group">
                    <label for="confirm_nouveau_mdp">Confirmer le nouveau mot de passe :</label>
                    <input type="password" id="confirm_nouveau_mdp" name="confirm_nouveau_mdp" required><br>
                </div>
                
                <!-- Ajout d'un champ caché pour indiquer que c'est pour changer le mot de passe -->
                <input type="hidden" name="changer_mot_de_passe" value="1">
                
                <input type="submit" value="Changer le mot de passe">
            </form>
        </section>
	  
	  
	  	<div class="deconnexion">
        	<a href="deconnexion.php" class="bouton-deconnexion">Déconnexion</a>
    	</div>
	  
    </section>
    
    <?php include("footer.php");
  
  ?>
    
    
    <!-- Script JavaScript pour afficher message de validation changement de nom... -->
    <script>
        // Vérifie si la page a été chargée avec un paramètre "success"
        const urlParams = new URLSearchParams(window.location.search);
        const changement_reussi = urlParams.get('success');
        if (changement_reussi === '1') {
            // Affiche une alerte indiquant que les informations ont été modifiées avec succès
            alert("Les informations ont été modifiées avec succès !");
        }
	</script>
 
</body>
</html>
