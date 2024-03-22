<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class db
{
    private $server = 'localhost';
    private $username = 'root'; 
    private $password = '';
    private $database = 'cafeteria';
    private $connection;
    function __construct()
    {
        try {
            $dsn = "mysql:host={$this->server}";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $this->connection->query("CREATE database if not exists {$this->database}");
            $stmt->execute();

            $this->connection->query("USE {$this->database}");

            // insert schema
            $CustomerTable = "CREATE table if not exists customers (
                CID INT AUTO_INCREMENT PRIMARY KEY,
                FirstName VARCHAR(50),
                LastName VARCHAR(50),
                Email VARCHAR(100),
                Phone VARCHAR(20),
                Address VARCHAR(255),
                Password VARCHAR(255)
            )";
            $this->connection->query($CustomerTable);


            
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}
$db = new db(); 
echo "Connected"; 
?>
