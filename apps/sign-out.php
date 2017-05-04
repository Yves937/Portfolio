<?php

//	Déconnexion de l'utilisateur

	//	Démarrage de la session
	include 'session-start.php';

	//	Suppression de l'utilisateur de la session
	session_destroy();

	//unset($_SESSION['username']);

	//	Redirection vers la page d'authentification et de création de compte
	header('Location: ../index.php?page=page3');
	exit();