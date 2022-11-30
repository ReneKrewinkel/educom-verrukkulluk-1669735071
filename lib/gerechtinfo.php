<?php

class gerechtinfo {

    private $connection;
    private $usr;

    public function __construct($connection) {
        $this->connection = $connection;
        $this->usr = new user ($connection);
    }

    private function selectUser ($usr_id) {
        $data = $this->usr->selecteerUser($usr_id);
        return($data);
            
        }

    public function deleteFavorite($gerecht_id, $user_id) {

        $sql = "delete from gerechtinfo where record_type = 'F' and gerecht_id = $gerecht_id and user_id = $user_id";

        $result = mysqli_query($this->connection, $sql);

    }    

    public function addFavorite($gerecht_id, $user_id) { 

        $sql = "insert into gerechtinfo (gerecht_id, record_type, user_id) VALUES ($gerecht_id, 'F', $user_id)";

        $result = mysqli_query($this->connection, $sql);
        
    }    
  
    public function selecteerInfo($gerecht_id, $record_type) {

        $sql = "select * from gerechtinfo where gerecht_id = $gerecht_id and record_type = '$record_type'";
        $return =[];
        
        $result = mysqli_query($this->connection, $sql);

            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

                $arr = [];
                $usr_arr = [];

                $arr =[

                    "id" => $row["id"],
                    "gerecht_id" => $row["gerecht_id"],
                    "record_type" => $row["record_type"],
                    "datum" => $row["datum"],
                    "nummeriekveld" => $row["nummeriekveld"],
                    "tekstveld" => $row["tekstveld"],

                ];

            if ($record_type == 'O' || $record_type == 'F') {
                $usr_id = $row ["user_id"];
                $usr_arr = $this->selectUser($usr_id);

            }   
            
            $return[] = $arr + $usr_arr;

            }

        return($return);

    }


}

?>
