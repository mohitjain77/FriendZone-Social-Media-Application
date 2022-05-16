<?php
    class DatabaseConnection
    {
        public function __construct()
        {
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
            if($conn->connect_error)
            {
                die("<h1>Database connection failed</h1>");
            }
            return $this->conn = $conn;
        }
    
    }
?>