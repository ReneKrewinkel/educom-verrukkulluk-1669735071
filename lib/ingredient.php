<?php

class ingredient {

    private $connection;
    private $art;

    public function __construct($connection) {
        $this->connection = $connection;
        $this->art = new artikel($connection);
    }

    public function selectArtikel($art_id) {
        $data = $this->art->selecteerArtikel($art_id);
        return($data);

    }
  
    public function selecteerIngredient($ingredient_id) {

        $sql = "select * from ingredient where id = $ingredient_id";
        
        $result = mysqli_query($this->connection, $sql);
        $ingredient = mysqli_fetch_array($result, MYSQLI_ASSOC);

        return($ingredient);

    }


}
