<?php

class boodschappen {
    private $connection;
    private $ingredient;

    public function __construct($connection) {
        $this->connection = $connection;
        $this->ingredient = new ingredient ($connection);
    }

    private function selectIngredienten($gerecht_id){
        $data = $this->ingredient->selecteerIngredient($gerecht_id);
        return($data);
    }

    public function ophalenBoodschappen($user_id) {
        $return = [];     
        $sql = "select * from boodschappen where user_id = $user_id";
        $result = mysqli_query($this->connection, $sql);
        while ($boodschappen = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $return[] = $boodschappen;
        }
        return($return);
    }

    private function aantalBerekenen($ingredient) {
        $ingredientAantal = $ingredient["aantal"];
        $ingredientVerpakking = $ingredient["verpakking"];
        $aantalBerekening = $ingredientAantal/$ingredientVerpakking;
        return $aantalBerekening;
    }
    
    private function exactAantal($ingredient, $boodschap) {
        $aantalBerekening = $this->aantalBerekenen($ingredient);
        $berekeningExactaantal = $boodschap["exact_aantal"] + $aantalBerekening;
        return ($berekeningExactaantal);
    }

    private function artikelBijwerken($boodschap, $ingredient) {
        $berekeningExactaantal = $this->exactAantal($ingredient, $boodschap);
        $aantal = ceil($berekeningExactaantal);
        $sql = "UPDATE boodschappen SET aantal = $aantal, exact_aantal = $berekeningExactaantal
        WHERE id = $boodschap[id]";
        $result = mysqli_query($this->connection, $sql);
    }

    private function toevoegenartikel($ingredient, $user_id) {
        $artikel_id = $ingredient["artikel_id"];
        
        $aantalBerekening = $this->aantalBerekenen($ingredient);
        $aantal = ceil($aantalBerekening);
        $sql = "INSERT INTO boodschappen (artikel_id, user_id, aantal, exact_aantal) VALUES ($artikel_id, $user_id, $aantal, $aantalBerekening)";
        $result = mysqli_query($this->connection, $sql); 
        return TRUE;
    }

    private function artikelOpLijst($artikel_id, $user_id) {
        $boodschappen = $this->ophalenBoodschappen($user_id);
        foreach ($boodschappen as $boodschap) {
            if($boodschap["artikel_id"] == $artikel_id) {
                return($boodschap);
            }
        }
        return FALSE; 
    }

    public function boodschappenToevoegen($gerecht_id, $user_id) {    
        $ingredienten = $this->selectIngredienten($gerecht_id);
        foreach ($ingredienten as $ingredient) {

            $lijst = $this->artikelOpLijst($ingredient["artikel_id"], $user_id);
            if(!$lijst) {    
                $this->toevoegenartikel($ingredient, $user_id);
            } else {
                $this->artikelBijwerken($lijst, $ingredient);
            }

        }              

    }
      
} 

?>