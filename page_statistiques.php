
<?php
session_start();
include 'connexion.php';

if (isset($_SESSION['connecter']) != TRUE){
  header("Location: http://localhost/interface/pagelogin.php");
}
if (isset($_POST['déconnexion']))
{
  $_SESSION = array();
  session_destroy();
  header("Location: http://localhost/interface/pagelogin.php");
}
if (isset($_POST['Recherche_fini']))
{
  header("Location: http://localhost/interface/pageprincipale.php");
}

$conn_hockey = connexion_hockey();
$joueur = $_SESSION['joueur_select'];


$stats_joueur = $conn_hockey->prepare("select SAISON, EQUIPE, PARTIE_JOUER, BUT, ASSIST, POINTS, MINPENALITE, PLUSMINUS from hockey_player.stats_joueur_actif where NOM_JOUEUR = ? order by SAISON");
$stats_joueur->execute(array($joueur));

?>

<html>
<head>
<title>Page Stats de joueur</title>
</head>
<body>


<table align="center" border="2px" style="width:500px; line-height:40px;">
  <tr>
    <th colspan="8"><h2><?php echo $joueur; ?></h2></th>
  </tr>
  <t>
    <th>Saison</th>
    <th>Équipe</th>
    <th>GP</th>
    <th>G</th>
    <th>A</th>
    <th>Pts</th>
    <th>PIM</th>
    <th>+/-</th>
  </t>
<?php
while ($annee = $stats_joueur->fetch())
{
?>
  <tr>
    <td><?php echo $annee['SAISON']; ?></td>
    <td><?php echo $annee['EQUIPE']; ?></td>
    <td><?php echo $annee['PARTIE_JOUER']; ?></td>
    <td><?php echo $annee['BUT']; ?></td>
    <td><?php echo $annee['ASSIST']; ?></td>
    <td><?php echo $annee['POINTS']; ?></td>
    <td><?php echo $annee['MINPENALITE']; ?></td>
    <td><?php echo $annee['PLUSMINUS']; ?></td>
  </tr>
<?php
}
?>
</table>

<?php
$stats_joueur->closeCursor();
?>

<form method="post" action="page_statistiques.php">
  <input type="submit" name="déconnexion" value="Se déconnecter">
  <input type="submit" name="Recherche_fini" value="Rechercher un autre joueur">
</form>
</body>
</html>
