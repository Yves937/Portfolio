<?php
/*
Page colg.php

La colonne de gauche de votre site (page incluse).


Liste des fonctions :
--------------------------
Aucune fonction
--------------------------


Liste des informations / erreurs :
--------------------------
Aucune information / erreur
--------------------------
*/
?>
        <div id="statistiques"><h1>Espace Membres</h1>
                <a href="<?php echo ROOTPATH; ?>/stats.php?see=nb_membres"><?php echo $num1 = get('nb_membres'); if($num1 <= 1) echo ' membre inscrit'; else echo ' membres inscrits'; ?></a><br/>
                <a href="<?php echo ROOTPATH; ?>/stats.php?see=connectes"><?php echo $num2 = get('connectes'); if($num2 <= 1) echo ' visiteur'; else echo ' visiteurs' ?></a><br/>
                <a href="<?php echo ROOTPATH; ?>/stats.php?see=passed">Dernières visites</a><br/>
        </div>
<?php

function get($type) 
{
        if($type == 'nb_membres')
        {
                $count = sqlquery("SELECT COUNT(*) AS nbr FROM membres", 1);
                return $count['nbr'];
        }
        
        else if($type == 'connectes')
        {
                $count = sqlquery("SELECT COUNT(*) AS nbr FROM connectes", 1);
                return $count['nbr'];
        }
        
        else
        {
                return 0;
        }
}
 

 

function mepd($date)
{
        if(intval($date) == 0) return $date;
        
        $tampon = time();
        $diff = $tampon - $date;
        
        $dateDay = date('d', $date);
        $tamponDay = date('d', $tampon);
        $diffDay = $tamponDay - $dateDay;
        
        if($diff < 60 && $diffDay == 0)
        {
                return 'Il y a '.$diff.'s';
        }
        
        else if($diff < 600 && $diffDay == 0)
        {
                return 'Il y a '.floor($diff/60).'m et '.floor($diff%60).'s';
        }
        
        else if($diff < 3600 && $diffDay == 0)
        {
                return 'Il y a '.floor($diff/60).'m';
        }
        
        else if($diff < 7200 && $diffDay == 0)
        {
                return 'Il y a '.floor($diff/3600).'h et '.floor(($diff%3600)/60).'m';
        }
        
        else if($diff < 24*3600 && $diffDay == 0)
        {
                return 'Aujourd\'hui à '.date('H\hi', $date);
        }
        
        else if($diff < 48*3600 && $diffDay == 1)
        {
                return 'Hier à '.date('H\hi', $date);
        }
        
        else
        {
                return 'Le '.date('d/m/Y', $date).' à '.date('h\hi', $date).'.';
        }
}
?>