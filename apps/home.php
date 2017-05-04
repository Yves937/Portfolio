<?php
//	Démarrage de la session
	include 'session-start.php';
	//	Récupération du résultat de l'authentification (vaut TRUE s'il est authentifié et FALSE s'il ne l'est pas)
	$isAuthenticated = array_key_exists('user', $_SESSION);

	//	Si l'utilisateur n'est pas authentifié
	/*if(!$isAuthenticated)
	{
		//	Enregistrement du message d'échec
		$_SESSION['alertMessages']['error'][] = 'Veuillez vous authentifier !';

		//	Redirection vers la page d'authentification et de création de compte
		header('Location: ./index.php?page=page3');
		exit();
	}*/

	if($isAuthenticated)
	{
		//	Connexion à la base de données
		include 'connection.php';

		$requete = 
			'SELECT
				Users.username,
				Users.id
			FROM
				Users
			';

		$resultSet = $connection->query($requete);
		$messages = $resultSet->fetchAll();
		$user = $_SESSION['user'];
		//var_dump($_SESSION);
	}
	include 'views/home.phtml';
?>
