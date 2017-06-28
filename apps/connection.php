<?php
	//	Connexion à la base de données
	$connection = new PDO
	(
		'mysql:host=yveskaerxh776.mysql.db;dbname=yveskaerxh776;charset=utf8',
		'yveskaerxh776',
		'Vectron67',
		[
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		]
	);
	var_dump($connection);
?>