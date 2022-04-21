<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport"
     content="width=device-width, initial-scale=1, user-scalable=yes">
     <link rel="stylesheet" href="style2.css" media="screen" type="text/css"/>
</head>
<body>
  <div id="container">
      <form method="POST"action="stockage.php">
        <h1>Nouveau compte</h1>
        <label><b>Nom</b></label>
        <input type="text" placeholder="Entrer nom" name="nom" required> </input>
        <label><b>Prenom</b></label>
        <input type="text" placeholder="Entrer prenom" name="prenom" required> </input> 
        <label><b>Adresse Mail</b></label>
        <input type="text" placeholder="Entrer adressemail" name="adressemail" required> </input>
        <label><b>Username</b></label>
        <input type="text" placeholder="Entrer username" name="username" required> </input>
        <label><b>Password</b></label>
        <input type="password" placeholder="Entrer password" name="password" required> </input> <br><br>
        <input type="submit" name="submit" value="Creer compte"> 
        <?php
              if(isset($_GET['erreur']))
              {
                  $err = $_GET['erreur'];
                  if($err==1)
                      echo "<p style='color:red'>Echec lors de la creation</p>";
              }
        ?>
      </form>
  </div>
</body>
</html>