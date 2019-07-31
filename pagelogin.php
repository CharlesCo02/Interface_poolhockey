<?php

include 'connexion.php';
$conn = connexion_user();
session_start();

if (isset($_SESSION['connecter'])){
  header("Location: http://localhost/interface/pageprincipale.php");
}
if (isset($_POST['inscription']))
{
  header("Location: http://localhost/interface/pageinscription.php");
}
if(isset($_POST['connexion']))
{
  $utilisateur = htmlspecialchars($_POST['login']);
  $mot_de_passe = sha1($_POST['mot_de_passe']);

  if(!empty($_POST['login']) AND !empty($_POST['mot_de_passe']) )
  {
    $verif_connexion = $conn->prepare("select * from user_pool.user where USER_NAME = ? and USER_PASSWORD = ?");
    $verif_connexion->execute(array($utilisateur, $mot_de_passe));
    $compte_exist = $verif_connexion->rowCount();

    if ($compte_exist == 1)
    {
      $message = "";
      $donnes = $verif_connexion->fetch();
      $_SESSION['user_id'] = $donnes['ID'];
      $_SESSION['user_name'] = $donnes['USER_NAME'];
      $verif_connexion->closeCursor();
      $_SESSION['connecter'] = "oui";
      header("Location: http://localhost/interface/pageprincipale.php");
    }
    else {
      $message = "La combinaison utilisateur, mot de passe saisie n'est pas valide";
    }
  }
}

 ?>
 <html>
 <head>
 <title>Page connexion</title>
 </head>
 <body>
 <div align="center">
 <h1>Connexion</h1><br />
 <form method="post" action="pagelogin.php">
   <table>
     <tr>
       <td align="right">
         <label for="utilisateur">Utilisateur :</label>
       </td>
       <td>
         <input type="text" placeholder="Votre utilisateur" id="utilisateur" name="login" value="" />
       </td>
     </tr>
     <tr>
       <td align="right">
         <label for="password">Mot de passe :</label>
       </td>
       <td>
         <input type="password" placeholder="Votre password" id="password" name="mot_de_passe" value="" />
       </td>
     </tr>
     <tr>
   </table>
   <input type="submit" name="connexion" value="Se connecter">
   <input type="submit" name="inscription" value="Se créer un compte">
 </form>
<?php
if(isset($_SESSION['message_inscription']))
{
  $message = $_SESSION['message_inscription'];
}
if(isset($message))
{
  echo $message;
}
?>
</div>
</body>
</html>
