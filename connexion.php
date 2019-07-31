<?php
/*
Fonctions qui permettent la connexion aux bases de données.
*/

function connexion_user(){
  $bdd = new PDO('mysql:host=localhost;dbname=user_pool;charset=utf8', 'root', '');
  return $bdd;
}

function connexion_hockey(){
  $bdd = new PDO('mysql:host=localhost;dbname=hockey_player;charset=utf8', 'root', '');
  return $bdd;
}

?>
