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

    public function addFavorite($gerecht_id) { 

        if(!isset($gerecht_id)) return false;  

        $sql = "insert into gerechtinfo (record_type, gerecht_id)
        VALUES ('F', $gerecht_id)"; 

        return ($this->connection->query($sql));
        
    }    

    public function deleteFavorite($gerecht_id) {

        $sql = "delete from gerechtinfo where gerecht_id= $gerecht_id";

        return ($this->connection->query($sql));

    } 

    public function addWaardering($gerecht_id, $rating) {

        $sql = "INSERT INTO gerecht_info (gerecht_id, record_type, nummeriekveld)

        VALUES ($gerecht_id, 'W', $rating)";

        return ($this->connection->query($sql));

    }

    public function berekenGemiddelde($gerecht_id) {

        $sql="SELECT nummeriekveld FROM gerechtinfo WHERE record_type='W' AND gerecht_id=$gerecht_id";
        $result = mysqli_query($this->connection, $sql);

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $waarderingen[] = 
                $row["nummeriekveld"]
            ;
        }

        $count= count($waarderingen);
        $sum = array_sum($waarderingen);
        $berekening=$sum/$count;
        $berekeningRounded=round($berekening);
        return $berekeningRounded;
        }

        public function showSter($gerecht_id) {
            $berekening = $this->berekenGemiddelde($gerecht_id);
            if ($berekening <=1) {
                $value="*";
            } elseif ($berekening <=2) {
                $value="**";
            } elseif ($berekening <=3) {
                $value="***";
            } elseif ($berekening <=4) {
                $value="****";
            } elseif ($berekening <=5) {
                $value="*****";
            } else $value="";
            return $value;
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
