<?php

class Database
{
    private static $connection;
    private static $pool = [];

    public static function getConnection()
    {
        // Check if a connection exists in the pool
        if (!empty(self::$pool)) {
            // Retrieve a connection from the pool
            return array_pop(self::$pool);
        }

        // If no connection is available, create a new one
        if (!self::$connection) {

            self::$connection = self::makeConnection("localhost","root",'Adeyemi$20','task2');
            // Adjust the connection details based on your database configuration
        }

        return self::$connection;
    }

    private static function makeConnection($servername,$username,$password,$dbName){
        try {
        
            $conn = new PDO("mysql:host=$servername;port=3306;dbname=$dbName", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;
    
        } catch(PDOException $e) {
    
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public static function releaseConnection($connection)
    {
        // Check if a transaction is active before rolling back
        if ($connection->inTransaction()) {
            $connection->rollBack();
        }
        self::$pool[] = $connection;
    }
}
?>
