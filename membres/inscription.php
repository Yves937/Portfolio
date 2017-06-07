<?php
/*
Page inscription.php

Permet de s'inscrire.

Quelques indications : (utiliser l'outil de recherche et rechercher les mentions données)

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

if(isset($_SESSION['membre_id']))
{
	header('Location: '.ROOTPATH.'./index.php?page=0');
	exit();
}

/********Entête et titre de page*********/

$titre = 'Inscription 1/2';

include('includes/haut.php'); //contient le doctype, et head.

/**********Fin entête et titre***********/
?>

<!--Colonne gauche-->
		<div id="colonne_gauche">
		<?php
		include('includes/colg.php');
		?>
		</div>

<!--Contenu-->
		<div id="contenu">
			<div id="map">
				<a href="./index.php?page=page0">Accueil</a> => <a href="./index.php?page=page4">Inscription 1/2</a>
			</div>

<!-- prérampli les champs corrects en cas d'erreurs de façon à ne pas tout retaper -->
		<?php
			if(array_key_exists('erreurs', $_SESSION) AND ($_SESSION['erreurs'] > 0))
			{
			?>
			<div class="border-red">
			<h1>Note :</h1>
			<p>
			Lors de votre dernière tentative d'inscription, des erreurs sont survenues, en voici la liste :<br/>
			<?php
				echo $_SESSION['nb_erreurs'];
				echo $_SESSION['pseudo_info'];
				echo $_SESSION['mdp_info'];
				echo $_SESSION['mdp_verif_info'];
				echo $_SESSION['mail_info'];
				echo $_SESSION['mail_verif_info'];
				echo $_SESSION['date_naissance_info'];
				echo $_SESSION['qcm_info'];
				echo $_SESSION['captcha_info'];

			?>
			Nous vous avons pré-rempli les champs qui étaient corrects.<br/>
			Note : la partie QCM et image est entièrement à refaire, que vous vous soyez trompé ou non.
			</p>
			</div>
			<?php
			}
	?>
			
			<h1>Formulaire d'inscription</h1>
			<p>Bienvenue sur la page d'inscription de mon site !<br/>
			Merci de remplir ces champs pour continuer.</p>
			<!-- Formulaire mis à jour : -->
			<form action="membres/trait-inscription.php" method="post" name="Inscription">
				<fieldset><legend>Identifiants</legend>
					<label for="pseudo" class="float-right">Pseudo :</label> <input type="text" name="pseudo" id="pseudo" size="30" /> <em>(compris entre 3 et 32 caractères)</em><br />
					<label for="mdp" class="float-right">Mot de passe :</label> <input type="password" name="mdp" id="mdp" size="30" /> <em>(compris entre 4 et 50 caractères)</em><br />
					<label for="mdp_verif" class="float-right">Mot de passe (vérification) :</label> <input type="password" name="mdp_verif" id="mdp_verif" size="30" /><br />
					<label for="mail" class="float-right">Mail :</label> <input type="text" name="mail" id="mail" size="30" /> <br />
					<label for="mail_verif" class="float-right">Mail (vérification) :</label> <input type="text" name="mail_verif" id="mail_verif" size="30" /><br />
					<label for="date_naissance" class="float-right">Date de naissance :</label> <input type="text" name="date_naissance" id="date_naissance" size="30" /> <em>(format JJ/MM/AAAA)</em><br/>
				</fieldset>
				<fieldset><legend>Charte du site et protection anti-robot</legend>
					<?php
					include('includes/charte.php');
					?>
					
					<h1>Système anti-robot :</h1>
					
					<p>Qu'est-ce que c'est ?<br/>
					Pour lutter contre l'inscription non désirée de robots qui publient du contenu non désiré sur les sites web,
					nous avons décidé de mettre en place un systèle de sécurité.<br/>
					Aucun de ces systèmes n'est parfait, mais nous espérons que celui-ci, sans vous être inacessible sera suffisant
					pour lutter contre ces robots.<br/>
					Il est possible que certaines fois, l'image soit trop dure à lire ; le cas échéant, actualisez la page jusqu'à avoir une image lisible.<br/>
					Si vous êtes dans l'incapacité de lire plusieurs images d'affilée, <a href="../views/contact.phtml">contactez-nous</a>, nous nous occuperons de votre inscription.</p>
					<label for="captcha" class="float">Entrez les 8 caractères (majuscules ou chiffres) contenus dans l'image :</label> <input type="text" name="captcha" id="captcha"><br/>
					<img src="membres/captcha.php" />
				</fieldset>
				<div class="center"><input type="submit" value="Inscription" /></div>
			</form>
		</div>

<!--bas-->
		<?php
		include('includes/bas.php');
		mysql_close();
		?>