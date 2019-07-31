
<?php
session_start();
include 'connexion.php';
$conn_hockey = connexion_hockey();
if (isset($_SESSION['connecter']) != TRUE){
  header("Location: http://localhost/interface/pagelogin.php");
}

if (isset($_POST['déconnexion']))
{
  $_SESSION = array();
  session_destroy();
  header("Location: http://localhost/interface/pagelogin.php");
}

if (isset($_POST['choix_recherche']))
{
  if (isset($_POST['recherche_joueur']))
  {
    $_SESSION['joueur_select'] = $_POST['recherche_joueur'];
    header("Location: http://localhost/interface/page_statistiques.php");
  
  }
  else {
    $message = "Sélectionner un joueur pour compléter la recherche";
  }
}

?>

<html>
<head>
<title>Page principale</title>
</head>
<body>

<?php
if (isset($_POST['Rechercher']))
{
  $nom_joueur = "%" .$_POST['joueur']. "%";
  $recherche_joueur = $conn_hockey->prepare("select NOM_JOUEUR, EQUIPE, AGE_JOUEUR from hockey_player.stats_joueur_actif where NOM_JOUEUR like ? and SAISON = '2018-19'");
  $recherche_joueur->execute(array($nom_joueur));
  $verif_res = $recherche_joueur->rowCount();

  if ($verif_res > 0)
  {
    ?>
    <form method="post" action="pageprincipale.php">
    <select name="recherche_joueur">
    <?php
    while ($row = $recherche_joueur->fetch())
    {
      echo "<option value='$row[0]'> Nom du joueur : ".$row[0]."&nbsp;&nbsp;&nbsp; Équipe : ".$row[1]."&nbsp;&nbsp;&nbsp; Age : ".$row[2]."</option>";
    }
    ?>
    </select><br />
    <input type="submit" name="choix_recherche" value="Choisir">
    </form>
<?php
  }
}
?>
<form method="post" action="pageprincipale.php">
  <label for="joueur">Nom du joueur :</label>
  <input type="text" name="joueur" id="joueur" placeholder="Nom du joueur" value="">
  <input type="submit" name="Rechercher" value="Rechercher">
  <input type="submit" name="déconnexion" value="Se déconnecter">
</form>
<?php
if(isset($message))
{
  echo $message;
}
?>
</body>
</html>
