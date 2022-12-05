<?php

require_once("lib/database.php");
require_once("lib/artikel.php");
require_once("lib/user.php");
require_once("lib/keukentype.php");
require_once("lib/ingredient.php");
require_once("lib/gerechtinfo.php");
require_once("lib/gerecht.php");
require_once("lib/boodschappen.php");

/// INIT
$db = new database();
$art = new artikel($db->getConnection());
$usr = new user($db->getConnection());
$keu = new keukentype($db->getConnection());
$ing = new ingredient($db->getConnection());
$inf = new gerechtinfo($db->getConnection());
$ger = new gerecht($db->getConnection());
$lij = new boodschappen($db->getConnection());

// VERWERK 
// $data = $ger->selecteerGerecht(4);

// Selecteer meerdere gerechten #11
// $selectGerechtArray = $ger-> selecteerGerecht(1);
// echo "<pre>";
// var_dump($selectGerechtArray);

// boodschappenlijst Opdracht #12

// $ophalenBoodschappen = $lij->ophalenBoodschappen(1);
// echo "<pre>";
// var_dump($ophalenBoodschappen);

// boodschappen toevoegen ($gerecht_id, $user_id)

$addBoodschappen = $lij->boodschappenToevoegen(1,1);
echo "<pre>";
var_dump($addBoodschappen);

// RETURN
// echo "<pre>";
// var_dump($data); 