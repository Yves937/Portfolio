<?php
//	Création d'un compte utilisateur (traitement du formulaire de création de compte)

	//	Si le formulaire a correctement été soumis
if(array_key_exists('username', $_POST) AND array_key_exists('passwordHash', $_POST))
{
	//	Récupération du username et du mot de passe saisis (et suppression des espaces indésirables)
	$username = trim($_POST['username']);
	$password = trim($_POST['passwordHash']);
	//	Récupération de l'utilisateur ayant le username saisi
		//	Connexion à la base de données
		include 'connection.php';
		//	Définition de la requête
		$query =
		'SELECT
			id,
			username,
			passwordHash
		FROM
			Users
		WHERE
			username = ?
		';
		//	Préparation de la requête
		$resultSet = $connection->prepare($query);
		//	Exécution de la requête
		$resultSet->execute([$username]);
		//	Récupération de l'utilisateur demandé
		$user = $resultSet->fetch();
	//	var_dump($user, $user!==false, $password, password_verify($password, $user['password']));exit();

		//	Si un utilisateur a le username saisi et si le mot de passe saisi et le bon
		if($user!==false AND password_verify($password, $user['passwordHash']))

		{	//	Démarrage de la session
			include 'session-start.php';

			//	Enregistrement de l'utilisateur dans la session
			$_SESSION['user'] = 
				[
				'id' => $user['id'],
				'username' => $user['username']
				];
            //var_dump($user)
				//	Redirection vers la page d'accueil
			header('Location:../index.php?page=home');
			exit();
		}
	
}

	//	Redirection vers la page d'authentification et de création de compte
	header('Location: ../index.php?page=page3');
	exit();
?>