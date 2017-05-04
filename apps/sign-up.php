<?php

//	Création d'un compte utilisateur (traitement du formulaire de création de compte)

	//	Si le formulaire a correctement été soumis
if(array_key_exists('email', $_POST) AND array_key_exists('username', $_POST) AND array_key_exists('passwordHash', $_POST))
{
	//	Récupération du username et du mot de passe saisis (et suppression des espaces indésirables)
	$email  = trim($_POST['email']);
	$username = trim($_POST['username']);
	$password = trim($_POST['passwordHash']);

	//	Création de la clé de hachage du mot de passe
	$passwordHash = password_hash($password, PASSWORD_DEFAULT);

	//	Ajout de l'utilisateur et récupération du résultat (réussite ou échec)

	//	Connexion à la base de données
	include 'connection.php';

	//	Définition de la requête
	$query =
		'INSERT INTO 
			Users
			(email, username, passwordHash)
		VALUES
			(?, ?, ?)
		';
	
	//	Préparation de la requête
	$resultSet = $connection->prepare($query);

	//	Exécution de la requête
	$resultSet->execute([$email, $username, $passwordHash]);

	//$userAdded = ($resultSet->rowCount()===1);
	//var_dump($resultSet);
}
//var_dump($resultSet);
//	Redirection vers la page d'authentification et de création de compte
header("Location: ../index.php?page=page3");
exit();
?>
