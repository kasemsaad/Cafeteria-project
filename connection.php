


<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class db
{
    private $server = 'localhost';
    private $username = 'root'; 
    private $password = '';
    private $database = 'Cafeterie';
    private $connection;
    function __construct()
    {
        try {
            "CREATE DATABASE IF NOT EXISTS Cafeterie";
            $dsn = "mysql:host={$this->server};dbname={$this->database}";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

}
$db = new db(); // Create an instance of the db class and establish connection
echo "Connected"; // If this is printed, it means the connection was successful
?>
