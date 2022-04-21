<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport"
     content="width=device-width, initial-scale=1, user-scalable=yes">
     <link rel="stylesheet" href="style1.css" media="screen" type="text/css"/>
</head>
<body>
  <div id="container">
    <form method="POST"action="connexion.php">
        <h1> Identification </h1> 
        <label><b>Username</b></label>
        <input type="text" placeholder="Entrer username" name="username">

        <label><b>Password</b></label>
        <input type="password" placeholder="Entrer password" name="password">
        <input type="submit" name="submit" value="LOGIN"> </input> <a href="nouveauCompte.php"><input type="button" value="NOUVEAU COMPTE"></a>
        <?php
              // Gestion des differents type d'erreurs lors de la connexion ou creation d'un nouveau compte
              if(isset($_GET['erreur'])) // veification si une variable erreur(via url) a bien une valeur 
              {
                  $err = $_GET['erreur'];
                  if($err==1)
                      echo "<p style='color:red'>Username ou password incorrect</p>";
                  if($err==2)
                      echo "<p style='color:red'>Compte non existant</p>";
                  if($err==3)
                      echo "<p style='color:red'>Nombre de joueurs max atteint</p>";
              }
              if(isset($_GET['creer'])) // verification si une variable creer(via url) a bien une valeur
              {
                  $creer = $_GET['creer'];
                  if($creer==1)
                      echo "<p style='color:green'>Compte a bien ete creer</p>";
              }
              if(isset($_GET['deconnexion'])) // verification si une variable deconnexion(via url) a bien une valeur(username)
              { 
                  $var = $_GET['deconnexion'];
                  $connect = 0;
                  echo "<p style='color:green'>Deconnexion reussie : ".$var."</p>";
                  $connexion = oci_connect('c##asivaco_a',  'asivaco_a', 'dbinfo'); // connexion a la base de donnee oracle de l'universite
                  $ordre = oci_parse($connexion, 'update utilisateur
                                                  set Connected = :connected
                                                  where Username = :user_N'); // prepation de la requete sql pour mettre a jour la valeur de l'attribut connected a 0 dans la table utilisateur pour deconnecter l'utilisateur
                  oci_bind_by_name($ordre, ":connected", $connect); // bind les valeur dans la requete
                  oci_bind_by_name($ordre, ":user_N", $var);
                  oci_execute($ordre); // excution de la requete sql
              }
        ?>
    </form>
  </div>
</body>
</html>