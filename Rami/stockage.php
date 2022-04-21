<?php
		$connexion = oci_connect('c##asivaco_a',  'asivaco_a', 'dbinfo');

		function echecCreation($url) 
		{
		    ob_start();
		    header('Location: '.$url);
		    ob_end_flush();
		    exit();
		}

		function reussiteCreation($url) 
		{
		    ob_start();
		    header('Location: '.$url);
		    ob_end_flush();
		    exit();
		}
		$options = [
    		'cost' => 12,
		];
		$connected = 0;
		$passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT, $options); // hashage du password avant de le stocker dans la base de donnée
		$ordre = "insert into utilisateur values (:user_N, :nom, :prenom, :pass_W, :connected, :adressM)";
		$p = oci_parse($connexion, $ordre);
		oci_bind_by_name($p, ":nom", $_POST['nom']);
		oci_bind_by_name($p, ":prenom", $_POST['prenom']);
		oci_bind_by_name($p, ":adressM", $_POST['adressemail']);
		oci_bind_by_name($p, ":user_N", $_POST['username']);
		oci_bind_by_name($p, ":connected", $connected);
		oci_bind_by_name($p, ":pass_W", $passwordHash);
		$temp = oci_execute($p);
		if($temp)
		{
			oci_close($connexion);
			$url = 'index.php?creer=1';
			reussiteCreation($url);
		}
		else
		{
			oci_close($connexion);
			$url = 'nouveauCompte.php?erreur=1';
			echecCreation($url);
		}
?>