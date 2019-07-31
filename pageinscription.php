<?php
include 'connexion.php';
$conn = connexion_user();
session_start();

if (isset($_SESSION['connecter']) == "oui"){
  header("Location: http://localhost/interface/pageprincipale.php");
}

if (isset($_POST['inscription']))
{
  $utilisateur = htmlspecialchars($_POST['login']);
  $mot_de_passe = sha1($_POST['mot_de_passe']);
  $mot_de_passe2 = sha1($_POST['mot_de_passe2']);
  $adresse_courriel = htmlspecialchars($_POST['adresse_courriel']);
  $adresse_courriel2 = htmlspecialchars($_POST['adresse_courriel2']);

  if(!empty($_POST['login']) AND !empty($_POST['mot_de_passe']) AND !empty($_POST['mot_de_passe2']) AND !empty($_POST['adresse_courriel']) AND !empty($_POST['adresse_courriel2']))
  {
    $len_login = strlen($utilisateur);
    $len_password = strlen($mot_de_passe);
    $len_courriel = strlen($adresse_courriel);
    $nb_erreur = 0;

    if ($len_login > 200)
    {
      $erreur_login = "L'utilisateur doit être composé de 200 caractères et moins";
      unset($utilisateur);
      $nb_erreur = $nb_erreur + 1;
    }
    else {
      $verif_login = $conn->prepare("select * from user_pool.user where USER_NAME = ?");
      $verif_login->execute(array($utilisateur));
      $doublon_login = $verif_login->rowCount();

      if ($doublon_login > 0)
      {
        $erreur_login = "Le nom d'utilisateur que vous avez saisie est déjà pris";
        $nb_erreur = $nb_erreur + 1;
      }
    }
    if ($len_password > 200)
    {
      $erreur_password = "Le mot de passe doit être composé de 200 caractères et moins";
      $nb_erreur = $nb_erreur + 1;
    }
    else {
      if ($mot_de_passe == $mot_de_passe2)
      {
        $verif_password = $conn->prepare("select * from user_pool.user where USER_PASSWORD = ?");
        $verif_password->execute(array($mot_de_passe));
        $doublon_password = $verif_password->rowCount();

        if ($doublon_password > 0)
        {
          $erreur_password = "Le mot de passe que vous avez saisie est déjà pris";
          $nb_erreur = $nb_erreur + 1;
        }
      }
      else {
        $erreur_password = "Les mots de passe ne correspondent pas";
        $nb_erreur = $nb_erreur + 1;
      }
    }
    if ($len_courriel > 320)
    {
      $erreur_courriel = "Le courriel doit être composé de 320 caractères et moins";
      unset($adresse_courriel);
      unset($adresse_courriel2);
      $nb_erreur = $nb_erreur + 1;
    }
    else {
      if ($adresse_courriel == $adresse_courriel2){
        if (filter_var($adresse_courriel, FILTER_VALIDATE_EMAIL))
        {
          $verif_email = $conn->prepare("select * from user_pool.user where USER_MAIL = ?");
          $verif_email->execute(array($adresse_courriel));
          $doublon_email = $verif_email->rowCount();

          if ($doublon_email > 0){
            $erreur_courriel = "L'adresse courriel est déjà utilisée";
            unset($adresse_courriel);
            unset($adresse_courriel2);
            $nb_erreur = $nb_erreur + 1;
          }
        }
        else {
          $erreur_courriel = "L'adresse courriel que vous avez rentré n'est pas valide";
          unset($adresse_courriel);
          unset($adresse_courriel2);
          $nb_erreur = $nb_erreur + 1;
        }
      }
      else {
        $erreur_courriel = "Les adresses courriels ne correspondent pas";
        unset($adresse_courriel2);
        $nb_erreur = $nb_erreur + 1;
      }
    }
    if ($nb_erreur > 0)
    {
      $erreur = "";

      if (isset($erreur_login))
      {
        $erreur = $erreur. $erreur_login. '<br />';
      }
      if (isset($erreur_password))
      {
        $erreur = $erreur. $erreur_password. '<br />';
      }
      if (isset($erreur_courriel))
      {
        $erreur = $erreur. $erreur_courriel;
      }
    }
    else {
      try {
        $req_sql = "insert into user_pool.user(USER_NAME, USER_PASSWORD, USER_EMAIL) values(?, ?, ?)";
        $inscription = $conn->prepare($req_sql);
        $inscription->execute(array($utilisateur, $mot_de_passe, $adresse_courriel));
        $inscription->closeCursor();
        $_SESSION['message_inscription'] = "Votre compte a été créé !";
        header("Location: http://localhost/interface/pagelogin.php");
      }
      catch (Exception $e)
      {
        echo $req_sql. "<br>". $e->getMessage();
      }

    }

  }
  else{
    $erreur = "Tous les champs doivent être remplis";
  }
}
?>
<html>
<head>
<title>Page Inscription</title>
</head>
<body>
<div align="center">
<h1>Inscription</h1><br />
<form method="post" action="pageinscription.php">
  <table>
    <tr>
      <td align="right">
        <label for="utilisateur">Saisissez votre utilisateur :</label>
      </td>
      <td>
        <input type="text" placeholder="Votre utilisateur" id="utilisateur" name="login" value="<?php if (isset($utilisateur)) {echo $utilisateur;} ?>" />
      </td>
    </tr>
    <tr>
      <td align="right">
        <label for="password">Définir votre mot de passe :</label>
      </td>
      <td>
        <input type="password" placeholder="Votre password" id="password" name="mot_de_passe" value="" />
      </td>
    </tr>
    <tr>
    <tr>
      <td align="right">
        <label for="password2">Confirmer votre mot de passe :</label>
      </td>
      <td>
          <input type="password" placeholder="Confirmation password" id="password2" name="mot_de_passe2" value="" />
      </td>
    </tr>
    <tr>
      <td align="right">
        <label for="courriel">Adresse courriel associé au compte :</label>
      </td>
      <td>
        <input type="email" placeholder="Votre adresse courriel" id="courriel" name="adresse_courriel" value="<?php if (isset($adresse_courriel)) {echo $adresse_courriel;} ?>" />
      </td>
    </tr>
    <tr>
      <td align="right">
        <label for="courriel2">Confirmez votre adresse courriel  :</label>
      </td>
      <td>
        <input type="email" placeholder="Confirmation adresse courriel" id="courriel2" name="adresse_courriel2" value="<?php if (isset($adresse_courriel2)) {echo $adresse_courriel2;} ?>" />
      </td>
    </tr>
  </table>
  <input type="submit" name="inscription" value="créer compte">
</form>
<?php
if(isset($erreur)){
  echo $erreur;
}
?>
</div>
</body>
</html>
