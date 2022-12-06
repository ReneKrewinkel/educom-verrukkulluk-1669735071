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
$data = $ger->selecteerGerecht(4);

// Selecteer meerdere gerechten #11
// $selectGerechtArray = $ger-> selecteerGerecht(1);
// echo "<pre>";
// var_dump($selectGerechtArray);

// boodschappenlijst Opdracht #12

// $ophalenBoodschappen = $lij->ophalenBoodschappen(1);
// echo "<pre>";
// var_dump($ophalenBoodschappen);

// boodschappen toevoegen ($gerecht_id, $user_id)

$addBoodschappen = $lij->boodschappenToevoegen(3,1);
echo "<pre>";
var_dump($addBoodschappen);

// RETURN
// echo "<pre>";
// var_dump($data); 
?>

<html>
<body>

	<form action="" method="GET" name="">
		<table>
			<tr>
				<td><input type="text" name="k" value="<?php echo isset($_GET['k']) ? $_GET['k'] : ''; ?>" placeholder="Zoekwoorden invullen" /></td>
				<td><input type="submit" name="" value="Zoek" /></td>
			</tr>
		</table>
	</form>

	<?php

		// Check of zoekwoord word gegeven
		if (isset($_GET['k']) && $_GET['k'] != ''){

			// bewaar zoekwoord van de url
			$k = trim($_GET['k']);

			// base query en words string maken
			$query_string = "SELECT * FROM artikel WHERE ";
			$display_words = "";

			// zoekwoorden onderscheiden
			$keywords = explode(' ', $k);
			foreach($keywords as $word){
				$query_string .= "keywords LIKE '%".$word."%' or";
				$display_words .= $word." ";
			}
			$query_string = substr($query_string, 0, strlen($query_string) - 3);

			// connect database

			$conn = mysqli_connect("localhost", "hamilton", "root", "verrukkulluk");
			$query = mysqli_query($conn, $query_string);
			$result_count = mysqli_num_rows($query);

			// resultaten returns bekijken

			if ($result_count > 0){

				// zoekresultaat count
				echo '<br/><div class="left"><b><u>' .$result_count. '</u></b> resultaten gevonden</div>';
				echo 'Je zocht op <i>'.$display_words.'</i><hr /><br/>';

				echo '<table class="search">';
				
				// alle zoeksresultaten weergeven aangebruiker
				while ($row = mysqli_fetch_assoc($query)){
					echo '<tr>
							<td>'.$row['naam'],'</td>
						 </tr>
						 <tr>
							<td>'.$row['omschrijving'],'</td>
						</tr>';
				}

				echo '</table>';
			}
			else
				echo 'Geen zoekresultaten gevonden. Geef andere zoekwoorden op.';

		}
		else
			echo '';


	?>

</body>
</html>