<?php
session_start(); // Démarre une nouvelle session

// Connexion à la base de données
$serveur = "we96pm.myd.infomaniak.com";
$utilisateur = "we96pm_system";
$motDePasse = "2107Emilienb";
$baseDeDonnees = "we96pm_projet";

$connexion = new mysqli($serveur, $utilisateur, $motDePasse, $baseDeDonnees);

// Vérifie la connexion
if ($connexion->connect_error) {
    die("La connexion à la base de données a échoué : " . $connexion->connect_error);
}

// Gestion sécurisée de l'inscription de l'utilisateur, seulement si le formulaire à soumis un formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'], $_POST['motDePasse'])) { // isset() vérifie si une variable est définie
  	$email = $_POST['email']; // Récupère l''email de la base de données et le place dans la variable "email"
    $motDePasse = $_POST['motDePasse'];

    // Requête SQL pour insérer l'utilisateur dans la base de données avec le mot de passe haché
    $requete = $connexion->prepare("INSERT INTO connexion (nom, prenom, email, motDePasse) VALUES (?, ?, ?, ?)"); // points d'interrogation comme espaces réservés pour ces nom,.... 
    $motDePasseHache = password_hash($motDePasse, PASSWORD_DEFAULT);
    $requete->bind_param("ssss", $_POST['nom'], $_POST['prenom'], $email, $motDePasseHache); // association de chaque points d'interrogations avec la valeur correspondante

    if ($requete->execute()) {
        $message = "Utilisateur enregistré avec succès.";
    } else {
        $message = "Erreur lors de l'enregistrement de l'utilisateur.";
    }
}

// Pareil qu'au dessus mais ici c'est pour lorsqu'on se connecte 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['emailConnexion'], $_POST['MDPConnexion'])) {
    $email = $_POST['emailConnexion'];
    $motDePasse = $_POST['MDPConnexion'];

    // Requête SQL pour vérifier si l'utilisateur existe
    $requete = $connexion->prepare("SELECT id, motDePasse FROM connexion WHERE email = ?"); // On cherhce dans la colonne email l'email qui à été soumis
    $requete->bind_param("s", $email);
    $requete->execute();
    $resultat = $requete->get_result();

  if ($resultat->num_rows == 1) { // Si une correspondance dans la table à été trouvé
        $utilisateur = $resultat->fetch_assoc(); // fech_assoc extrait ligne de résultats sous forme tableau associatif où clés du tableau sont les noms des colonnes de la table
        $motDePasseHasher = $utilisateur['motDePasse'];

        // Vérifie si le mot de passe correspond
        if (password_verify($motDePasse, $motDePasseHasher)) {
            
		  	// Authentification réussie
            $_SESSION['id'] = $utilisateur['id']; // Stocke l'ID de l'utilisateur dans la session
			$_SESSION['prenom'] = $utilisateur['prenom']; // Stocke le prénom de l'utilisateur dans la session
            
		  	// Redirection vers la page sécurisée avec un paramètre succès pour afficher un message
			header("Location: accueil-securise.php?connexion=1");
			exit();
        } else {
            // Mot de passe incorrect
            $message = "Mot de passe incorrect.";
        }
    }
    else {
        // Utilisateur non trouvé
        $message = "Utilisateur non trouvé.";
    }
}


// Ferme la connexion à la base de données
$connexion->close();
?>





<!DOCTYPE html>
    <html lang="fr-FR">
        <head>
            <title> Vehicules d'éxception </title>
            <meta charset= 'utf-8'>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
		  <link rel="stylesheet" href="CSS/Css-acceuil2.css">
            <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
        </head>
        <body>
            
            <script>
            // Exécute le script lorsque la page est complètement chargée
            window.onload = function() {
            // Affiche l'alerte avec le message PHP
            alert("<?php echo $message; ?>");
            };
            </script>
            
        <?php include("header.php"); ?>
        
        <nav>
        <ul>
            <li><a href="#ancre1">Qui sommes nous ?</a></li>
            <li><a href="Vehicule-collection\collection-pasSecu\Vehicule-collection.php">Véhicules de collections</a></li>
            <li><a href="Vehicule-sportif/sportifs-pasSecu/Vehicule-sportif.php">Véhicules sportifs</a></li>
            <li><a href="Vehicule-atypique\Atypique-pasSecu\Vehicule-atypique.php" >Véhicules atypiques</a></li>
        </ul>
        </nav>
        <br><br><br>
        
        <?php
    // Vérifie si la variable de session 'prenom' est définie
    if (isset($_SESSION['prenom'])) {
        // Affiche le message de bienvenue avec le prénom de l'utilisateur
        echo "<p>Connexion réussie ! Bienvenue, " . $_SESSION['prenom'] . "!</p>";
    }
    ?>
        
        <div class="slideshow-container">
            <div class="slideshow">
                <img src="Images\Mercedes\Mercedes-300-SLR-Ulhenhaut-Coupé-3.png" alt="Mercedes-Benz 300 SLR (1955)" class="bandeau">
                <img src="Images\Ferrari\Ferrari-288-gto.png" alt="Ferrari-288-gto" class="bandeau">
                <img src="Images/Lamborghini/Lamborghini-Diablo.png" alt="Lamborghini Diablo" class="bandeau">
                <img src="Images\Mercedes\Mercedes-300-Sl-2.png" alt="Mercedes-300-Sl" class="bandeau">
                <img src="Images\Ferrari\Ferrari-F8-SPIDER_2.png" alt="Ferrari-F8-SPIDER" class="bandeau">
                <img src="Images\Aston-Martin\AstonMartin-DB6.png" alt="AstonMartin-DB6" class="bandeau" >
                <img src="Images\Mercedes\Mercedes-230-SL.png" alt="Mercedes-Benz 230 SL Pagode (1963-1967)" class="bandeau">
                <img src="Images/Lamborghini/Lamborghini-Countach-2.png" alt="Lamborghini Countach" class="bandeau">
                <img src="Images\Aston-Martin\AstonMartin-DBS-GT-Zagato.png" alt="Aston Martin DBS GT Zagato 2022" class="bandeau">
                <img src="Images\Porsche\Porsche-930-turbo.png" alt="Porsche-930-turbo" class="bandeau" >
                <img src="Images\Porsche\Porsche_718_Cayman_GT4.png" alt="Porsche_718_Cayman_GT4" class="bandeau">
                <img src="Images\Mercedes\Mercedes-300-SL-roadster-2.png" alt="Mercedes-300-SL-roadster" class="bandeau">
                <img src="Images\Mercedes\Mercedes_classe_S65_AMG_3.png" alt="Mercedes-300-SL-roadster" class="bandeau">
                <img src="Images\Aston-Martin\AstonMartin-V12-Speedster.png" alt="Aston Martin V12 Speedster 2023" class="bandeau">
                <img src="Images\Mercedes\Mercedes_SLR.png" alt="Mercedes SLR"  class="bandeau">
                <img src="Images\Ferrari\Ferrari_458_spider_3.png" alt="Ferrari 458 spider " class="bandeau">
                <img src="Images\Ferrari\Ferrari-Dino-246-GT.png" alt="Ferrari Dino 246 GT " class="bandeau">
            </div>
        </div>
        <br><br><br>
        
        <div class="citation">
            <div class="citation-text">
                <p> La voiture est l’expression directe de la personnalité de son conducteur.</p>
            </div>
            <div class="auteur">
                -Enzo Ferrari 
            </div>
        </div>
        <br><br><br>

            
        <div class="container">
            <div class="social-icons">
                <h2> Nos reseaux : </h2>
                <a href="mailto:votre@email.com"><img src="Images/gmail.png" alt="Email"></a>
                <a href="https://www.facebook.com/votrepage" target="_blank"><img src="Images/Facebook.png" alt="Facebook"></a>
                <a href="https://twitter.com/votrecompte" target="_blank"><img src="Images/Twitter.png" alt="Twitter"></a>
                <a href="https://instagram.com/votrecompte" target="_blank"><img src="Images/instagram.png" alt="Instagram"></a>
            </div>
            <div class="block">
                <h2>Voiture du moment</h2>
			  	<a href="Page-individuel-voitures\individuel-pasSecu\Ferrari-250-GTO.php" class="voiture-link">
                <img src="Images\Ferrari\Ferrari-250-gto.png" alt="Ferrari 250 GTO 1962" class="voiture-moment">
                <h3>Ferrari 250 GTO 1962 </h3>
            </div>
        </div>  
        
        <div class="nouveautes">
            <h2>Nouveautés</h2>
            <div class="voitures">
                <a href="Page-individuel-voitures/individuel-pasSecu/Ferrari-SF90.php" class="voiture-link">
                    <img src="Images\Ferrari\Ferrari-SF90.png" alt="Ferrari-SF90" class="voiture">
                </a>
                <a href="Page-individuel-voitures/individuel-pasSecu/Porsche-911-gt3RS.php" class="voiture-link">
                    <img src="Images\Porsche\porsche-911-gt3RS.png" alt="porsche-911-gt3RS" class="voiture">
                </a>
                <a href="Page-individuel-voitures/individuel-pasSecu/Mercedes-540K.php" class="voiture-link">
                    <img src="Images\Mercedes\Mercedes-540K.png" alt="Mercedes-540K" class="voiture">
                </a>
            </div>
        </div>
        <br><br><br>
        
        <h2 id="ancre1"> Qui sommes nous ? </h2>
        <br><br><br> 
        <div class = "div4"> 
            <p> Bienvenue chez Vehicules d'éxception, une référence incontournable dans le monde des véhicules. Fondée sur une passion profonde pour les
            voitures de prestige, notre entreprise est née de l'aspiration à offrir une expérience unique à tous les amateurs d'automobiles rares et
            exclusives.<br><br>
            Nos origines remontent à une fascination pour les lignes élégantes, les performances exaltantes et l'histoire captivante des véhicules d'exception.
            Forts de cette passion, nous avons établi Vehicules d'éxception dans le but de partager notre amour pour l'automobile avec d'autres passionnés.
            <br><br>
            Chez Vehicules d'éxception, nous sommes bien plus qu'une simple plateforme d'achat et de vente de voitures haut de gamme. Nous sommes des gardiens
            de trésors mécaniques, des conservateurs de l'histoire automobile. Nous offrons des services de gardiennage haut de gamme pour assurer la préservation
            optimale de vos précieuses machines lorsque vous ne les conduisez pas.<br><br>
            Mais notre engagement ne s'arrête pas là. Nous croyons fermement que les véritables passionnés méritent de vivre pleinement leur passion. C'est pourquoi
            nous proposons également un service de location de voitures d'exception, permettant à nos clients de vivre des moments inoubliables au volant de véhicules
            de rêve.<br><br>
            Ce qui nous distingue des autres concessionnaires, c'est notre approche holistique de l'expérience automobile. Nous ne vendons pas simplement des voitures ;
            nous créons des liens entre les passionnés, entre les conducteurs et leurs rêves, entre le passé et le présent de l'automobile. Notre équipe dévouée est composée
            d'experts passionnés qui partagent votre amour pour les voitures d'exception et qui sont déterminés à vous offrir un service personnalisé et attentif à chacune de
            vos demandes.<br><br>
            Chez Vehicules d'éxception, chaque voiture est une histoire à raconter, un rêve à réaliser, une passion à partager. Venez nous rendre visite et laissez-nous vous
            emmener dans un voyage extraordinaire à travers le monde fascinant de l'automobile d'exception.<br> <br></p>
        </div>
        <br><br><br>
        

        <div class="compte-essaie">
            <!-- Formulaire de création de compte -->
            <div class="creation-compte">
                <h2>Création de compte</h2>
                <form id="creationCompteForm" action="connexion.php" method="post">
                    <input type="text" id="nom" name="nom" placeholder="Nom" required>
                    <input type="text" id="prenom" name="prenom" placeholder="Prénom" required>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                    <input type="password" id="motDePasse" name="motDePasse" placeholder="Mot de passe à maximun 10 chiffres " required>
                    <button type="submit">Créer un compte</button>
                </form>
                <p id="messageCreationCompte"></p> <!-- Message pour afficher les résultats de l'inscription -->
            </div>

            <!-- Formulaire de connexion -->
            <div class="connexion">
                <h2>Connexion</h2>
                <form id="connexionForm" action="connexion.php" method="post">
                    <input type="email" id="emailConnexion" name="emailConnexion" placeholder="Email" required>
                    <input type="password" id="MDPConnexion" name="MDPConnexion" placeholder="Mot de passe" required>
                    <button type="submit">Se connecter</button>
                </form>
                <p id="messageConnexion"></p> <!-- Message pour afficher les résultats de la connexion -->
            </div>
		  
		  	<!-- Formulaire pour réinitialiser le mot de passe -->
    		<div class="mot-de-passe-oublie">
        		<h2>Mot de passe oublié</h2>
        		<form action="reinitialisationMDP.php" method="post">
            		<input type="email" name="email" placeholder="Email" required>
            		<button type="submit">Envoyer</button>
        		</form>
    		</div>
		  
        </div>
        
        <?php include("footer.php"); ?> 
       
        </body>
    </html>