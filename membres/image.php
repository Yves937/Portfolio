<?php
/*
Page image.php

Génère une image d'un texte passé en GET['tex'].

Quelques indications (utiliser l'outil de recherche et rechercher les mentions données) :

Liste des fonctions :
--------------------------
Aucune fonction
--------------------------


Liste des informations / erreurs :
--------------------------
Aucune information / erreur
--------------------------
*/

header ("Content-type: image/png");
$count = strlen($_GET['tex']);
$image = imagecreate($count*9, 18);

if(function_exists(imagecolorallocatealpha))
{
        $blanc = imagecolorallocatealpha($image, 255, 255, 255, 127);
}

else
{
        $blanc = imagecolorallocate($image, 255, 255, 255, 127);
}
$noir = imagecolorallocate($image, 0, 0, 0);

imagestring($image, 5, 1, 1, htmlspecialchars($_GET['tex'], ENT_QUOTES), $noir);
imagepng($image);
?>