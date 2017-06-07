<?php
	/*
	Page trait-inscription.php

	Permet de valider son inscription.

	Quelques indications : (utiliser l'outil de recherche et rechercher les mentions données)

	Liste des fonctions :
	--------------------------
	Aucune fonction
	--------------------------


	Liste des informations/erreurs :
	--------------------------
	Déjà inscrit (en cas de bug...)
	--------------------------
	*/

	session_start();
	header('Content-type: text/html; charset=utf-8');
	include('../includes/config.php');

	/********Actualisation de la session...**********/

	include('../includes/fonctions.php');
	connexionbdd();
	actualiser_session();



	/********Fin actualisation de session...**********/

	if(isset($_SESSION['membre_id']))
	{
		header('Location: '.ROOTPATH.'../index.php?page=page0');
		exit();
	}


if(array_key_exists('inscrit', $_SESSION) AND $_SESSION['inscrit'] == $_POST['pseudo'] && trim($_POST['inscrit']) != '')
{
	$informations = Array(/*Déjà inscrit (en cas de bug...)*/
						true,
						'Vous êtes déjà inscrit',
						'Vous avez déjà complété une inscription avec le pseudo <span class="pseudo">'.htmlspecialchars($_SESSION['inscrit'], ENT_QUOTES).'</span>.',
						' - <a href="'.ROOTPATH.'../index.php?page=page0">Retourner à l\'index</a>',
						ROOTPATH.'../index.php?page=page4',
						5
						);
	require_once('../information.php');
	exit();
}


function checkpseudo($pseudo)
{
	if($pseudo == '') return 'empty';
	else if(strlen($pseudo) < 3) return 'tooshort';
	else if(strlen($pseudo) > 32) return 'toolong';
		
	else
	{
		$result = sqlquery("SELECT COUNT(*) AS nbr FROM membres WHERE membre_pseudo = '".mysql_real_escape_string($pseudo)."'", 1);
		global $queries;
		$queries++;
			
		if($result['nbr'] > 0) return 'exists';
		else return 'ok';
	}
}

function checkmdp($mdp)
{
	if($mdp == '') return 'empty';
	else if(strlen($mdp) < 4) return 'tooshort';
	else if(strlen($mdp) > 50) return 'toolong';
		
	else
	{
		if(!preg_match('#[0-9]{1,}#', $mdp)) return 'nofigure';
		else if(!preg_match('#[A-Z]{1,}#', $mdp)) return 'noupcap';
		else return 'ok';
	}
}

	// verifie si les mots de passe correspondent bien

function checkmdpS($mdp, $mdp2)
{
	if($mdp != $mdp2 && $mdp != '' && $mdp2 != '') return 'different';
	else return checkmdp($mdp);
}

	// verifie si le format du mail avec un regexp

function checkmail($email)
{
	if($email == '') return 'empty';
	else if(!preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#is', $email)) return 'isnt';
		
	else
	{
		$result = sqlquery("SELECT COUNT(*) AS nbr FROM membres WHERE membre_mail = '".mysql_real_escape_string($email)."'", 1);
		global $queries;
		$queries++;
			
		if($result['nbr'] > 0) return 'exists';
		else return 'ok';
	}
}

	// verifie si les deux emails sont identiques
function checkmailS($email, $email2)
{
	if($email != $email2 && $email != '' && $email2 != '') return 'different';
	else return 'ok';
}

	// vérification  du format de la date de naissance et de l'âge de l'utilisateur

function birthdate($date)
{
	if($date == '') return 'empty';

	else if(substr_count($date, '/') != 2) return 'format';
	else
	{
		$DATE = explode('/', $date);
		if(date('Y') - $DATE[2] <= 4) return 'tooyoung';
		else if(date('Y') - $DATE[2] >= 135) return 'tooold';
			
		else if($DATE[2]%4 == 0)
		{
			$maxdays = Array('31', '29', '31', '30', '31', '30', '31', '31', '30', '31', '30', '31');
			if($DATE[0] > $maxdays[$DATE[1]-1]) return 'invalid';
			else return 'ok';
		}
			
		else
		{
			$maxdays = Array('31', '28', '31', '30', '31', '30', '31', '31', '30', '31', '30', '31');
			if($DATE[0] > $maxdays[$DATE[1]-1]) return 'invalid';
			else return 'ok';
		}
	}
}

	// vidage de la session

function vidersession()
{
	foreach($_SESSION as $cle => $element)
	{
		unset($_SESSION[$cle]);
	}
}

	// vérification de l'existance des champs du formulaire

$_SESSION['erreurs'] = 0;

	//Pseudo
	if(isset($_POST['pseudo']))
	{	
		$pseudo = trim($_POST['pseudo']);
		$pseudo_result = checkpseudo($pseudo);
		if($pseudo_result == 'tooshort')
		{
			$_SESSION['pseudo_info'] = '<span class="erreur">Le pseudo '.htmlspecialchars($pseudo, ENT_QUOTES).' est trop court, vous devez en choisir un plus long (minimum 3 caractères).</span><br/>';
			$_SESSION['form_pseudo'] = '';
			$_SESSION['erreurs']++;
		}
		
		else if($pseudo_result == 'toolong')
		{
			$_SESSION['pseudo_info'] = '<span class="erreur">Le pseudo '.htmlspecialchars($pseudo, ENT_QUOTES).' est trop long, vous devez en choisir un plus court (maximum 32 caractères).</span><br/>';
			$_SESSION['form_pseudo'] = '';
			$_SESSION['erreurs']++;
		}
		
		else if($pseudo_result == 'exists')
		{
			$_SESSION['pseudo_info'] = '<span class="erreur">Le pseudo '.htmlspecialchars($pseudo, ENT_QUOTES).' est déjà pris, choisissez-en un autre.</span><br/>';
			$_SESSION['form_pseudo'] = '';
			$_SESSION['erreurs']++;
		}
			
		else if($pseudo_result == 'ok')
		{
			$_SESSION['pseudo_info'] = '';
			$_SESSION['form_pseudo'] = $pseudo;
		}
		
		else if($pseudo_result == 'empty')
		{
			$_SESSION['pseudo_info'] = '<span class="erreur">Vous n\'avez pas entré de pseudo.</span><br/>';
			$_SESSION['form_pseudo'] = '';
			$_SESSION['erreurs']++;	
		}
	}

	else
	{
		header('Location: ./index.php?page=page0');
		exit();
	}

	//Mot de passe
	if(isset($_POST['mdp']))
	{
		$mdp = trim($_POST['mdp']);
		$mdp_result = checkmdp($mdp, '');
		if($mdp_result == 'tooshort')
		{
			$_SESSION['mdp_info'] = '<span class="erreur">Le mot de passe entré est trop court, changez-en pour un plus long (minimum 4 caractères).</span><br/>';
			$_SESSION['form_mdp'] = '';
			$_SESSION['erreurs']++;
		}
		
		else if($mdp_result == 'toolong')
		{
			$_SESSION['mdp_info'] = '<span class="erreur">Le mot de passe entré est trop long, changez-en pour un plus court. (maximum 50 caractères)</span><br/>';
			$_SESSION['form_mdp'] = '';
			$_SESSION['erreurs']++;
		}
		
		else if($mdp_result == 'nofigure')
		{
			$_SESSION['mdp_info'] = '<span class="erreur">Votre mot de passe doit contenir au moins un chiffre.</span><br/>';
			$_SESSION['form_mdp'] = '';
			$_SESSION['erreurs']++;
		}
			
		else if($mdp_result == 'noupcap')
		{
			$_SESSION['mdp_info'] = '<span class="erreur">Votre mot de passe doit contenir au moins une majuscule.</span><br/>';
			$_SESSION['form_mdp'] = '';
			$_SESSION['erreurs']++;
		}
			
		else if($mdp_result == 'ok')
		{
			$_SESSION['mdp_info'] = '';
			$_SESSION['form_mdp'] = $mdp;
		}
		
		else if($mdp_result == 'empty')
		{
			$_SESSION['mdp_info'] = '<span class="erreur">Vous n\'avez pas entré de mot de passe.</span><br/>';
			$_SESSION['form_mdp'] = '';
			$_SESSION['erreurs']++;

		}
	}

	else
	{
		header('Location: ./index.php?page=page0');
		exit();
	}

	//Mot de passe suite
	if(isset($_POST['mdp_verif']))
	{
		$mdp_verif = trim($_POST['mdp_verif']);
		$mdp_verif_result = checkmdpS($mdp_verif, $mdp);
		if($mdp_verif_result == 'different')
		{
			$_SESSION['mdp_verif_info'] = '<span class="erreur">Le mot de passe de vérification diffère du mot de passe.</span><br/>';
			$_SESSION['form_mdp_verif'] = '';
			$_SESSION['erreurs']++;
			if(isset($_SESSION['form_mdp'])) unset($_SESSION['form_mdp']);
		}
		
		else
		{
			if($mdp_verif_result == 'ok')
			{
				$_SESSION['form_mdp_verif'] = $mdp_verif;
				$_SESSION['mdp_verif_info'] = '';
			}
			
			else
			{
				$_SESSION['mdp_verif_info'] = str_replace('passe', 'passe de vérification', $_SESSION['mdp_info']);
				$_SESSION['form_mdp_verif'] = '';
				$_SESSION['erreurs']++;
			}
		}
	}

	else
	{
		header('Location: ./index.php?page=page0');
		exit();
	}

	//mail
	if(isset($_POST['mail']))
	{
		$mail = trim($_POST['mail']);
		$mail_result = checkmail($mail);
		if($mail_result == 'isnt')
		{
			$_SESSION['mail_info'] = '<span class="erreur">Le mail '.htmlspecialchars($mail, ENT_QUOTES).' n\'est pas valide.</span><br/>';
			$_SESSION['form_mail'] = '';
			$_SESSION['erreurs']++;
		}
		
		else if($mail_result == 'exists')
		{
			$_SESSION['mail_info'] = '<span class="erreur">Le mail '.htmlspecialchars($mail, ENT_QUOTES).' est déjà pris, <a href="../contact.php">contactez-nous</a> si vous pensez à une erreur.</span><br/>';
			$_SESSION['form_mail'] = '';
			$_SESSION['erreurs']++;
		}
			
		else if($mail_result == 'ok')
		{
			$_SESSION['mail_info'] = '';
			$_SESSION['form_mail'] = $mail;
		}
		
		else if($mail_result == 'empty')
		{
			$_SESSION['mail_info'] = '<span class="erreur">Vous n\'avez pas entré de mail.</span><br/>';
			$_SESSION['form_mail'] = '';
			$_SESSION['erreurs']++;	
		}
	}

	else
	{
		header('Location: ./index.php?page=page0');
		exit();
	}

	//mail suite
	if(isset($_POST['mail_verif']))
	{
		$mail_verif = trim($_POST['mail_verif']);
		$mail_verif_result = checkmailS($mail_verif, $mail);
		if($mail_verif_result == 'different')
		{
			$_SESSION['mail_verif_info'] = '<span class="erreur">Le mail de vérification diffère du mail.</span><br/>';
			$_SESSION['form_mail_verif'] = '';
			$_SESSION['erreurs']++;
		}
		
		else
		{
			if($mail_result == 'ok')
			{
				$_SESSION['mail_verif_info'] = '';
				$_SESSION['form_mail_verif'] = $mail_verif;
			}
			
			else
			{
				$_SESSION['mail_verif_info'] = str_replace(' mail', ' mail de vérification', $_SESSION['mail_info']);
				$_SESSION['form_mail_verif'] = '';
				$_SESSION['erreurs']++;
			}
		}
	}

	else
	{
		header('Location: ./index.php?page=page0');
		exit();
	}

	//date de naissance
	if(isset($_POST['date_naissance']))
	{
		$date_naissance = trim($_POST['date_naissance']);
		$date_naissance_result = birthdate($date_naissance);
		if($date_naissance_result == 'format')
		{
			$_SESSION['date_naissance_info'] = '<span class="erreur">Date de naissance au mauvais format ou invalide.</span><br/>';
			$_SESSION['form_date_naissance'] = '';
			$_SESSION['erreurs']++;
		}
		
		else if($date_naissance_result == 'tooyoung')
		{
			$_SESSION['date_naissance_info'] = '<span class="erreur">Agagagougougou areuh ? (Vous êtes trop jeune pour vous inscrire ici.)</span><br/>';
			$_SESSION['form_date_naissance'] = '';
			$_SESSION['erreurs']++;
		}
			
		else if($date_naissance_result == 'tooold')
		{
			$_SESSION['date_naissance_info'] = '<span class="erreur">Plus de 135 ans ? Mouais...</span><br/>';
			$_SESSION['form_date_naissance'] = '';
			$_SESSION['erreurs']++;
		}
			
		else if($date_naissance_result == 'invalid')
		{
			$_SESSION['date_naissance_info'] = '<span class="erreur">Le '.htmlspecialchars($date_naissance, ENT_QUOTES).' n\'existe pas.</span><br/>';
			$_SESSION['form_date_naissance'] = '';
			$_SESSION['erreurs']++;
		}
			
		else if($date_naissance_result == 'ok')
		{
			$_SESSION['date_naissance_info'] = '';
			$_SESSION['form_date_naissance'] = $date_naissance;
		}
		
		else if($date_naissance_result == 'empty')
		{
			$_SESSION['date_naissance_info'] = '<span class="erreur">Vous n\'avez pas entré de date de naissance.</span><br/>';
			$_SESSION['form_date_naissance'] = '';
			$_SESSION['erreurs']++;
		}
	}

	else
	{
		header('Location: ./index.php?page=page0');
		exit();
	}

	//qcm
	if($_SESSION['reponse1'] == $_POST['reponse1'] && $_SESSION['reponse2'] == $_POST['reponse2'] && $_SESSION['reponse3'] == $_POST['reponse3'] && isset($_POST['reponse1']) && isset($_POST['reponse2']) && isset($_POST['reponse3']))
	{
		$_SESSION['qcm_info'] = '';
	}

	else
	{
		$_SESSION['qcm_info'] = '<span class="erreur">Au moins une des réponses au QCM charte est fausse.</span><br/>';
		$_SESSION['erreurs']++;
	}


	//captcha
	if($_POST['captcha'] == $_SESSION['captcha'] && isset($_POST['captcha']) && isset($_SESSION['captcha']))
	{
		$_SESSION['captcha_info'] = '';
	}

	else
	{
		$_SESSION['captcha_info'] = '<span class="erreur">Vous n\'avez pas recopié correctement le contenu de l\'image.</span><br/>';
		$_SESSION['erreurs']++;
	}

	unset($_SESSION['reponse1'], $_SESSION['reponse2'], $_SESSION['reponse3']);
	unset($_SESSION['captcha']);

	/*************Fin étude******************/

	/********Entête et titre de page*********/
	if($_SESSION['erreurs'] > 0) $titre = 'Erreur : Inscription 2/2';
	else $titre = 'Inscription 2/2';

	include('../includes/haut.php'); //contient le doctype, et head.

	/**********Fin entête et titre***********/
?>
	<div id="colonne_gauche">
		<?php
		include('../includes/colg.php');
		?>
		</div>
		
		<div id="contenu">
			<div id="map">
<!-- Absence de lien à Inscription 2/2 volontaire -->
				<a href="../index.php?page=page0">Accueil</a> => Inscription 2/2
			</div>
<?php
	echo stripos('abcdefg', 'ab'); //echo 0, car a est le premier caractère de abcdefg;
	echo stripos('abcdefg', 'gh'); //FALSE
	echo stripos('abcdefg', 'd'); //echo 3;, car d est le 4e caractère de abcdefg

	if(0 == FALSE) echo 'Oooohhhh! 0 == False !!<br/>';
	else echo 'Oh non, 0 != False :(<br/>';
	if(0 === FALSE) echo 'Aahhhhhhh! 0 === False !!<br/>';
	else echo 'Oh, 0 !== False :x'; 
?>
<!--Test des erreurs et envoi-->
		<?php
		if($_SESSION['erreurs'] == 0)
		{
			$insertion = "INSERT INTO membres VALUES(NULL, '".mysql_real_escape_string($pseudo)."',
			'".md5($mdp)."', '".mysql_real_escape_string($mail)."',
			".time().", '".mysql_real_escape_string($date_naissance)."',
			'', '',
			'', '',
			'', '',
			'', '',
			".time().", 0)";
				
			if(mysql_query($insertion))
			{
				$queries++;
				vidersession();
				$_SESSION['inscrit'] = $pseudo;
				/*informe qu'il s'est déjà inscrit s'il actualise, si son navigateur
				bugue avant l'affichage de la page et qu'il recharge la page, etc.*/
		?>
			<h1>Inscription validée !</h1>
			<p>Nous vous remercions de vous être inscrit sur notre site, votre inscription a été validée !<br/>
			Vous pouvez vous connecter avec vos identifiants <a href="../index.php?page=page5">ici</a>.
			</p>
		<?php
			}
				
			else
			{
				if(stripos(mysql_error(), $_SESSION['form_pseudo']) !== FALSE) // recherche du pseudo
				{
					unset($_SESSION['form_pseudo']);
					$_SESSION['pseudo_info'] = '<span class="erreur">Le pseudo '.htmlspecialchars($pseudo, ENT_QUOTES).' est déjà pris, choisissez-en un autre.</span><br/>';
					$_SESSION['erreurs']++;
				}
					
				if(stripos(mysql_error(), $_SESSION['form_mail']) !== FALSE) //recherche du mail
				{
					unset($_SESSION['form_mail']);
					unset($_SESSION['form_mail_verif']);
					$_SESSION['mail_info'] = '<span class="erreur">Le mail '.htmlspecialchars($mail, ENT_QUOTES).' est déjà pris, <a href="../contact.php">contactez-nous</a> si vous pensez à une erreur.</span><br/>';
					$_SESSION['mail_verif_info'] = str_replace('mail', 'mail de vérification', $_SESSION['mail_info']);
					$_SESSION['erreurs']++;
					$_SESSION['erreurs']++;
				}
					
				if($_SESSION['erreurs'] == 0)
				{
						$sqlbug = true; //plantage SQL.
						$_SESSION['erreurs']++;
				}
			}
		}

		if(array_key_exists('erreurs', $_SESSION) && $_SESSION['erreurs'] > 0)
		{
			if($_SESSION['erreurs'] == 1) $_SESSION['nb_erreurs'] = '<span class="erreur">Il y a eu 1 erreur.</span><br/>';
			else $_SESSION['nb_erreurs'] = '<span class="erreur">Il y a eu '.$_SESSION['erreurs'].' erreurs.</span><br/>';
		?>
			<h1>Inscription non validée.</h1>
			<p>Vous avez rempli le formulaire d'inscription du site et nous vous en remercions, cependant, nous n'avons
			pas pu valider votre inscription, en voici les raisons :<br/>
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
				
			if($sqlbug !== true)
			{
		?>
		Nous vous proposons donc de revenir à la page précédente pour corriger les erreurs. (Attention, que vous
		l'ayez correctement remplie ou non, la partie sur la charte et l'image est à refaire intégralement.)</p>
		<div class="center"><a href="../index.php?page=page4">Retour</a></div>
		<?php
			}
				
			else
			{
		?>
		Une erreur est survenue dans la base de données, votre formulaire semble ne pas contenir d'erreurs, donc
		il est possible que le problème vienne de notre côté, réessayez de vous inscrire ou contactez-nous.</p>
			
		<div class="center"><a href="../index.php?page=page4">Retenter une inscription</a> - <a href="../views/contact.phtml">Contactez-nous</a></div>
		<?php
			}
		}
			?>
		</div>

		<?php
		include('../includes/bas.php');
		?>
<!--fin-->