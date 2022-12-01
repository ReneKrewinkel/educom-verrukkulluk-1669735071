<?php

class gerecht {

    private $connection;
    private $gerecht;

    public function __construct($connection) {
        $this->connection = $connection;
        $this->keu = new keukentype ($connection);
        $this->ing = new ingredient ($connection);
        $this->usr = new user ($connection);
        $this->inf = new gerechtinfo ($connection);
    }

    private function selectUser($user_id) {
            
        $user = $this->usr->selecteerUser($user_id);
        return($user);

    }
    
    private function selectIngredient($gerecht_id) {
            
        $ingredienten = $this->ing->selecteerIngredient($gerecht_id);
        return($ingredienten);
    
    }

    private function selectInfo($gerechtinfo_id, $record_type) {
            
        $gerechtinfo = $this->inf->selecteerInfo($gerechtinfo_id, $record_type);
        return($gerechtinfo);
    
        }

    private function selectKeukentype($keu_id) {
            
        $data = $this->keu->selecteerKeukentype($keu_id);
        return($data);
    
    }

    private function calcCalories($ingredienten) {    

        $calorien=0;  
        
        foreach ($ingredienten as $ingredient)  {
        $calorien +=($ingredient["calorieen"]*$ingredient["aantal"]);
        }

        return($calorien);
    
        }    

    private function calcPrice($ingredienten) {
            
        $prijs=0;

        foreach ($ingredienten as $ingredient)  {
        $prijs += ($ingredient["prijs"]*$ingredient["aantal"]);

        }
       
        return($prijs);
            
        }

    public function selecteerGerecht($gerecht_id=NULL) {
        
        $sql = "SELECT * FROM gerecht";
    
        if(!is_null($gerecht_id)) {
            
        $sql.=" WHERE id = $gerecht_id";
            
        }
    
        $return = [];
    
        $result = mysqli_query($this->connection, $sql);
    
        while ($gerecht = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    
            $gerecht_id = $gerecht["id"];
    
            $keuken_id = $gerecht["keuken_id"];
            $keuken = $this->selectKeukentype($keuken_id);
                
            $type_id = $gerecht["type_id"];
            $type= $this->selectKeukentype($type_id);
            
            $ingredienten = $this->selectIngredient($gerecht_id);
            
            $prijs= $this->calcPrice($ingredienten);
            
            $calorieen = $this->calcCalories($ingredienten);
            
            $user_id = $gerecht["user_id"];
            $user= $this->selectUser($user_id);
            
            $info = $this->selectInfo($gerecht_id, '$record_type');
            
            $gerechten[] = [
                "gerecht" => $gerecht,
                "keukentype" => $keuken,
                "user" => $user,
                "ingredienten" => $ingredienten,
                "gerechtinfo" => $info,
                "prijs" => $prijs,
                "calorieen" => $calorieen 
    
            ];    

    }

    return($gerechten);


}
    }

?>