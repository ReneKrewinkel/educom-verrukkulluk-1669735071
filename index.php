<?php
//// Allereerst zorgen dat de "Autoloader" uit vendor opgenomen wordt:
require_once("./vendor/autoload.php");

/// Twig koppelen:
$loader = new \Twig\Loader\FilesystemLoader("./templates");
/// VOOR PRODUCTIE:
/// $twig = new \Twig\Environment($loader), ["cache" => "./cache/cc"]);

/// VOOR DEVELOPMENT:
$twig = new \Twig\Environment($loader, ["debug" => true ]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

/******************************/

/// Next step, iets met je data doen. Ophalen of zo
require_once("lib/database.php");
require_once("lib/artikel.php");
require_once("lib/user.php");
require_once("lib/keukentype.php");
require_once("lib/ingredient.php");
require_once("lib/gerechtinfo.php");
require_once("lib/gerecht.php");
require_once("lib/boodschappen.php");

$db = new database();
$art = new artikel($db->getConnection());
$usr = new user($db->getConnection());
$keu = new keukentype($db->getConnection());
$ing = new ingredient($db->getConnection());
$inf = new gerechtinfo($db->getConnection());
$ger = new gerecht($db->getConnection());
$lij = new boodschappen($db->getConnection());
$data = $ger->selecteerGerecht();
// echo "<pre>";var_dump($data);

$conn = new mysqli('localhost', 'root', '', 'verrukkulluk');

if (isset($_POST['save'])) {
    $uID = $conn->real_escape_string($_POST['uID']);
    $rating = $conn->real_escape_string($_POST['rating']);
    $rating++;

    if (!$uID) {
        $conn->query("INSERT INTO gerecht (rating) VALUES ('$ratedIndex')");
        $sql = $conn->query("SELECT id FROM gerecht ORDER BY id DESC LIMIT 1");
        $uData = $sql->fetch_assoc();
        $uID = $uData['id'];
    } else
        $conn->query("UPDATE gerecht SET rating='$rating' WHERE id='$uID'");

    exit(json_encode(array('id' => $uID)));
}

$sql = $conn->query("SELECT id FROM gerecht");
$numR = $sql->num_rows;

$sql = $conn->query("SELECT SUM(rating) AS total FROM gerecht");
$rData = $sql->fetch_array();
$total = $rData['total'];


/*
URL:
http://localhost/index.php?gerecht_id=4&action=detail
*/

$gerecht_id = isset($_GET["gerecht_id"]) ? $_GET["gerecht_id"] : "";
$action = isset($_GET["action"]) ? $_GET["action"] : "homepage";
$user_id= 1;


switch($action) {

        case "homepage": {
            $data = $ger->selecteerGerecht();
            $template = 'homepage.html.twig';
            $title = "homepage";
            break;
        }

        case "detail": {
            $data = $ger->selecteerGerecht($gerecht_id);
            $template = 'detail.html.twig';
            $title = "detail pagina";
            break;
        }

        /// etc

}


/// Onderstaande code schrijf je idealiter in een layout klasse of iets dergelijks
/// Juiste template laden, in dit geval "homepage"
$template = $twig->load($template);


/// En tonen die handel!
echo $template->render(["title" => $title, "data" => $data]);
