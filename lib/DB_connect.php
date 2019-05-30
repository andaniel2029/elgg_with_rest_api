<?php
  
class DB_Connect {

    // Connecting to database
    public function connect() {
        // connecting to mysql
        $con = mysqli_connect(_elgg_config()->dbhost, _elgg_config()->dbuser, _elgg_config()->dbpass, _elgg_config()->dbname);
        // selecting database
        if (!$con) {
            error_log("[".date(DATE_RFC2822)."] Error: Unable to connect to MySQL." . PHP_EOL, 3, "web_error_log");
            error_log("[".date(DATE_RFC2822)."] Debugging errno: " . mysqli_connect_errno() . PHP_EOL, 3, "web_error_log");
            error_log("[".date(DATE_RFC2822)."] Debugging error: " . mysqli_connect_error() . PHP_EOL, 3, "web_error_log");
            exit;
        }
  
        // return database handler
        return $con;
    }
}

?>