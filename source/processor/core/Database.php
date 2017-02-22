<?php

class Database
{
    // main query method - not protected
    public static function doQuery($query){
        // Create connection
        $conn = new mysqli(DB_MYSQL_HOST, DB_MYSQL_USER, DB_MYSQL_PASSWORD, DB_MYSQL_DATABASE, DB_MYSQL_PORT);
        // Check connection
        if ($conn->connect_error) {
            if(DEV_MODE == true) {
                  dlog("Database Connection failed: " . $conn->connect_error);
            }
        }
        $result = $conn->query($query);
        if(!$result){
            return false;
        }else{
            if (isset($result->num_rows) && $result->num_rows > 0) {
                // output data of each row
                $result_set = array();
                while($row = $result->fetch_assoc()) {
                   array_push($result_set, $row);
                }
                $conn->close();
                return $result_set;
            } else {
                if(substr( $query, 0, 6 ) === "INSERT"){
                    return mysqli_insert_id($conn);
                }
                    return true;
                }
         }
    }

}