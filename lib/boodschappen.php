<?php

class boodschappen {

    private $connection;
    private $ingredient;
    private $user;
    private $boodschappen;

    public function __construct($connection) {
        $this->connection = $connection;
        $this->ingredient = new ingredient ($connection);
        $this->user = new user($connection);
    }

    private function selectIngredienten($gerecht_id){
        $data = $this->ingredient->selecteerIngredient($gerecht_id);
        return($data);
    }

    private function selectUser($user_id) {
        $data = $this->user->selecteerUser($user_id);
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

    private function artikelBijwerken($boodschap) {
        $sql = "UPDATE boodschappen SET aantal = CEILING(aantal)
        WHERE id = $boodschap[id]";
        
        $result = mysqli_query($this->connection, $sql);
        echo "Bijwerken<br>";
        var_dump($boodschap);
    }

    private function toevoegenArtikel($ingredient, $user_id) {
        $artikel_id = $ingredient["artikel_id"];
        $sql = "INSERT INTO boodschappen (artikel_id, user_id, aantal) VALUES ($artikel_id, $user_id, 1)";
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
                $this->artikelBijwerken($lijst);
            }
        }   


}
    }

?>
