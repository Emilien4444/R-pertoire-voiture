<?php
	session_start();
	session_unset();
	session_destroy();
	header("Location: accueil.php?deconnexion=1"); // redirige avec un paramètre de succès pour pouvoir afficher une alert en JavaScript
	exit();
?>