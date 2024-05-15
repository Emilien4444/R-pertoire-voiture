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

// Récupérer l'e-mail donné dans le formulaire formulaire
$email = $_POST['email'];

// Vérifier si l'e-mail existe dans la base de données
$sql = "SELECT * FROM connexion WHERE email = '$email'";
$result = $connexion->query($sql);

// L'e-mail existe dans la base de données
if ($result->num_rows > 0) {
    // Générer un nouveau mot de passe
    $nouveau_mot_de_passe = generateRandomString(10); 

    // Mettre à jour le mot de passe hashé dans la base de données
	$nouveau_mot_de_passe_hash = password_hash($nouveau_mot_de_passe, PASSWORD_DEFAULT);
	$sql_update = "UPDATE connexion SET MotDePasse = '$nouveau_mot_de_passe_hash' WHERE email = '$email'";
	$result_update = $connexion->query($sql_update);

  // Si la mise à jour se fais bien, alors un email est envoyé
    if ($result_update === TRUE) {
        $sujet = "Réinitialisation de votre mot de passe";
        $message = "Votre nouveau mot de passe est : " . $nouveau_mot_de_passe ;
        $headers = "From: VehiculedException@email.com";

        // Fonction mail() pour envoyer l'e-mail
        mail($email, $sujet, $message, $headers);

        echo "Un nouveau mot de passe a été envoyé à votre adresse e-mail.";
    } else {
        echo "Erreur lors de la réinitialisation du mot de passe.";
    }
} else {
    echo "Aucun compte n'est associé à cette adresse e-mail.";
}

// Fermer la connexion
$connexion->close();

// Fct qui génère un mot de passe de 10 chiffres
function generateRandomString($length = 10) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
  $randomString = ''; // CHaine de caractère vide pour stocket le mdp
    for ($i = 0; $i < $length; $i++) {
	  $randomString .= $characters[rand(0, $charactersLength - 1)]; // Ajoute un chiffre a chaque tour de la boucle
    }
    return $randomString;
}

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
            
        <?php include("header.php"); ?>
        
        <nav>
        <ul>
            <li><a href="#ancre1">Qui sommes nous ?</a></li>
            <li><a href="Vehicule-collection\collection-pasSecu\Vehicule-collection.php">Véhicules de collections</a></li>
            <li><a href="Vehicule-sportif\sportifs-pasSecu\Vehicule-sportif.php">Véhicules sportifs</a></li>
            <li><a href="Vehicule-atypique\Atypique-pasSecu\Vehicule-atypique.php">Véhicules atypiques</a></li>
        </ul>
        </nav>
        <br><br><br>
        
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
		  
		  <!-- "window.location.href=..." un peu de JavaScript avec un évèennement lorsque que le bouton est cliqué qui est de redirigé vers la page -->
		<button id="VoitureLegendaire" onclick="window.location.href='Page-individuel-voitures/individuel-pasSecu/LaVoitureLegendaire.php'">La Voiture Légendaire</button>

		  
        
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
                <a href="Page-individuel-voitures\individuel-pasSecu\Ferrari-SF90.php" class="voiture-link">
                    <img src="Images\Ferrari\Ferrari-SF90.png" alt="Ferrari-SF90" class="voiture">
                </a>
                <a href="Page-individuel-voitures\individuel-pasSecu\Porsche-911-gt3RS.php" class="voiture-link">
                    <img src="Images\Porsche\porsche-911-gt3RS.png" alt="porsche-911-gt3RS" class="voiture">
                </a>
                <a href="Page-individuel-voitures\individuel-pasSecu\Mercedes-540K.php" class="voiture-link">
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
			  	<form id="creationCompteForm" action="connexion.php" method="post">   <!-- Rediroge vers la page connexion.php-->
                    <input type="text" id="nom" name="nom" placeholder="Nom" required>
                    <input type="text" id="prenom" name="prenom" placeholder="Prénom" required>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                    <input type="password" id="motDePasse" name="motDePasse" placeholder="Mot de passe à maximun 10 chiffres " required>
                    <button type="submit">Créer un compte</button>
                </form>
                <p id="messageCreationCompte"></p> <!-- Message pour afficher les résultats du traitement -->
            </div>

            <!-- Formulaire de connexion -->
            <div class="connexion">
                <h2>Connexion</h2>
                <form id="connexionForm" action="connexion.php" method="post">
                    <input type="email" id="emailConnexion" name="emailConnexion" placeholder="Email" required>
                    <input type="password" id="MDPConnexion" name="MDPConnexion" placeholder="Mot de passe" required>
                    <button type="submit">Se connecter</button>
                </form>
                <p id="messageConnexion"></p> <!-- Message pour afficher les résultata -->
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
		<br><br>
        
        <?php include("footer.php"); ?> 
       
        </body>
    </html>
