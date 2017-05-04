<?php
	//	Connexion à la base de données
	$connection = new PDO
	(
		'mysql:host=localhost;dbname=portfolio;charset=utf8',
		'root',
		'troiswa',
		[
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		]
	);
	//var_dump($connection);
?>