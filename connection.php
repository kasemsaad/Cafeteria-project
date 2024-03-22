<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class db
{
    private $server = 'localhost';
    private $username = 'root'; 
    private $password = '';
    private $database = 'Cafeteria';
    private $connection;

    function __construct(){
        try {
            $dsn = "mysql:host={$this->server}";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $this->connection->query("CREATE DATABASE IF NOT EXISTS {$this->database}");
            $stmt->execute();
            $this->connection->exec("USE {$this->database}");


            $sql_create_table = "CREATE TABLE IF NOT EXISTS Customers (
                CustomerID INT AUTO_INCREMENT PRIMARY KEY,
                FirstName VARCHAR(50),
                LastName VARCHAR(50),
                Email VARCHAR(100),
                Phone VARCHAR(20),
                Address VARCHAR(255),
                Password VARCHAR(255)
            )";
            $connection->query($sql_create_table);
            

        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    

    function get_connection()
    {
        return $this->connection;
    }










}

$db = new db(); 
echo "Connected";
?>
