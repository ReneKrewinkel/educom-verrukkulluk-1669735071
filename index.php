<?php

require_once("lib/database.php");
require_once("lib/artikel.php");
require_once("lib/user.php");
require_once("lib/keukentype.php");
require_once("lib/ingredient.php");
require_once("lib/gerechtinfo.php");

/// INIT
$db = new database();
$art = new artikel($db->getConnection());
$usr = new user($db->getConnection());
$keu = new keukentype($db->getConnection());
$ing = new ingredient($db->getConnection());
$inf = new gerechtinfo($db->getConnection());

/// VERWERK 
$data = $inf->selectInfo(1, "O");

/// RETURN
echo "<pre>";
var_dump($data);