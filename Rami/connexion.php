<?php
	$connexion = oci_connect('c##asivaco_a',  'asivaco_a', 'dbinfo'); // connexion a la base de donneé sql

	/*
	* Fonction qui prend une url avec un type d'erreur(int) lors d'echec connexion et redirect sur index.php
	*/
	function echecConnexion($url) 
	{
	    ob_start();
	    header('Location: '.$url);
	    ob_end_flush();
	    exit();
	}

	/*
	* Fonction qui prend une url avec un id(attribuer au joueur) lors de la reussite de la connexion et redirect sur rami.php
	*/
	function reussiteConnexion($url)
	{
		ob_start();
	    header('Location: '.$url);
	    ob_end_flush();
	    exit();
	}

	if(!isset($_POST['submit']) || !isset($_POST['username']) || !isset($_POST['password']))
	{
		oci_close($connexion);
		$url = 'index.php?erreur=1';
		echecConnexion($url);
	}
	else
	{
		$p = oci_parse($connexion, 'select Password
									 from utilisateur
									 where Username = :user_N');
		oci_bind_by_name($p, ":user_N", $_POST['username']);
		oci_execute($p);
		$result = oci_fetch_array($p, OCI_BOTH);
		if($result!=false)
		{
			if(password_verify($_POST['password'], $result[0])) // verification si le password hasher dans la base de donnée et le meme qui a etait entrer dans le formulaire lors de la connexion en utilisant la fonction password_verify
			{
				$userConnected = 1;
				$ordre = oci_parse($connexion, 'select count(*)
									 			 from utilisateur
									 			 where Connected = :connected');
				oci_bind_by_name($ordre, ":connected", $userConnected);
				oci_execute($ordre);
				$nbUserConnected = oci_fetch_array($ordre, OCI_BOTH);
				if($nbUserConnected[0] < 4) // cas ou il y'a moins de 4 joueur, alors on autorise l'utilisateur a ce connecter au jeu
				{
					$temp = $nbUserConnected[0]+1;
					$ordre2 = oci_parse($connexion, 'update utilisateur
									 			 	 set Connected = :connected
									 			 	 where Username = :user_N');
					oci_bind_by_name($ordre2, ":connected", $userConnected);
					oci_bind_by_name($ordre2, ":user_N", $_POST['username']);
					oci_execute($ordre2);
					oci_close($connexion);
					$url = 'Jeu/rami.php?id='.$temp."&username=".$_POST['username'];
					reussiteConnexion($url);
				}
				else // cas ou il y'a deja 4 joueur alors on renvoie sur la page de connexion qui s'occupera d'affiche un message disant que le nombre de joueur maximal atteint
				{
					oci_close($connexion); // fermeture de la connexion
					$url = 'index.php?erreur=3';
					echecConnexion($url);
				}
			}
			else
			{
				oci_close($connexion);
				$url = 'index.php?erreur=1';
				echecConnexion($url);
			}
		}
		else
		{	
			oci_close($connexion);
			$url = 'index.php?erreur=2';
			echecConnexion($url);
		}
	}
?>