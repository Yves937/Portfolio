<?php

	//	Si la session n'a pas encore été démarrée
	if(session_status() !== PHP_SESSION_ACTIVE)
	{
		//	Démarrage de la session
		session_start();
		//	Génération d'un nouvel identifiant de session
		session_regenerate_id();
	}