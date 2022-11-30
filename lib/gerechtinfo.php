<?php

class gerechtinfo {

    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }
  
    public function selectInfo($gerecht_id, $recordtype) {

        $sql = "select * from gerechtinfo where gerecht_id = $gerecht_id'";
        
        $result = mysqli_query($this->connection, $sql);
        $artikel = mysqli_fetch_array($result, MYSQLI_ASSOC);

        return($gerechtinfo);

    }


}
