<?php

/**
 * A class file to connect to database
 */
class DB_CONNECT {
    private $con = null;
    private $bIsConnected = false;
 
    // constructor
    function __construct() {
        // connecting to database
        $this->connect();
    }

    function conn(){
        return $this->con;
    }
 
    // destructor
    function __destruct() {
        // closing db connection
        $this->close();
    }
 
    /**
     * Function to connect with database
     */
    function connect() {
        // import database connection variables
 
        // Connecting to mysql database
        // $con = mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD) or die(mysql_error());
        $this->con = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE, DB_PORT, DB_SOCKET);
        $this->bIsConnected = true; 
        // Selecing database
        // $db = mysql_select_db(DB_DATABASE) or die(mysql_error()) or die(mysql_error());
 
        // returing connection cursor
        return $this->con;
    }

    ///
    function validate_api_key($api_key) {
        $retVal = false;

        if($this->bIsConnected) {
            $prefix = 'api_key_';

            $q = "SELECT * FROM config WHERE value=?";

            $sql = $this->conn()->prepare($q);
            $sql->bind_param("s", $api_key);          
            $sql->execute();
            $result = $sql->get_result();
            if ($result->num_rows == 1) {        
                $row = $result->fetch_assoc();
                $retVal = substr($row['key'], strlen($prefix));
            }
        }
        return $retVal;
    }
 
    /**
     * Function to close db connection
     */
    function close() {
        // closing db connection
        if($this->con) {
            $this->con->close(); 
        }

    }
 
}
 
?>