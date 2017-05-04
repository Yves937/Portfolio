<?php

$page = 'home'; // Page d'accueil

$access = ['home', 'page1', 'page2', 'page3', 'page4']; // Liste des pages du site
if (isset($_GET['page']) && in_array($_GET['page'], $access)){

	$page = $_GET['page']; // Ici l'adresse de la page est bien passée en GET en enregistrée dans $page...
}
require('apps/skel.php'); // Adresse du controleur du squelette

?>
