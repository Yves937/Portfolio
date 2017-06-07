<?php
/*
Page index.php

Index du site.


Liste des fonctions :
--------------------------
Aucune fonction
--------------------------


Liste des informations/erreurs :
--------------------------
Aucune information/erreur
--------------------------
*/

session_start();
header('Content-type: text/html; charset=utf-8');
include('includes/config.php');

/********Actualisation de la session...**********/

include('includes/fonctions.php');
connexionbdd();
actualiser_session();

/********Fin actualisation de session...**********/

/********Entête et titre de page*********/

include('includes/haut.php'); //contient le doctype, et head.

/**********Fin entête et titre***********/
?>

		<div id="colonne_gauche">
		<?php
		include('includes/colg.php');
		?>
		</div>
		
		<div id="contenu">
			<div id="map">
				<a href="./index.php?page=page0">Accueil</a>
			</div>
			
			<h1>Bienvenue sur mon Espace Membres !</h1>
			<p>Ce site parlera de ... et est ouvert à tous.	
			Cependant,pour accéder à l'espace menbre vous devez vous : <a href='./index.php?page=page4' >inscrire</a>
			 <br> <br>
			
			Le Webmaster
			</p>
		</div>
		
		<?php
	include('includes/bas.php');
		mysql_close();
		?>