<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
$err = [];
if (isset ($_GET['err'])) {
  $err = json_decode($_GET['err'], true);
}
class db
{
    private $server = '127.0.0.1:3307';
    private $username = 'root'; 
    private $password = '';
    private $database = 'Cafeteria';
    private $connection;

    function __construct(){
        try {
            $dsn = "mysql:host={$this->server}";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $this->connection->query("CREATE database if not exists {$this->database}");
            $stmt->execute();
            $this->connection->exec("USE {$this->database}");

//////////Schema
                    
            $rooms = "CREATE TABLE IF NOT EXISTS rooms (
                room_no INT PRIMARY KEY,
                status ENUM('available', 'unavailable') NOT NULL,
                ext VARCHAR(20) NOT NULL
            )";
            $this->connection->query($rooms);

            $customers = "CREATE TABLE IF NOT EXISTS customers (
                customer_id INT AUTO_INCREMENT PRIMARY KEY,
                first_name VARCHAR(50) NOT NULL,
                last_name VARCHAR(50) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role ENUM('User', 'Admin') NOT NULL,
                room_no INT,
                phone VARCHAR(20),
                profile_image VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (room_no) REFERENCES rooms(room_no) ON DELETE CASCADE
            )";
            $this->connection->query($customers);

            $category = "CREATE TABLE IF NOT EXISTS categories (
                category_id INT AUTO_INCREMENT PRIMARY KEY,
                category_name VARCHAR(100) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
            $this->connection->query($category);

            $products = "CREATE TABLE IF NOT EXISTS products (
                product_id INT AUTO_INCREMENT PRIMARY KEY,
                product_name VARCHAR(100) NOT NULL,
                description TEXT,
                status ENUM('available', 'unavailable') NOT NULL,
                price DECIMAL(10, 2) NOT NULL,
                image VARCHAR(255),
                category_id INT,
                FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
            )";
            $this->connection->query($products);

            $orders = "CREATE TABLE IF NOT EXISTS orders (
                order_id INT AUTO_INCREMENT PRIMARY KEY,
                customer_id INT NOT NULL,
                total_amount DECIMAL(10, 2) NOT NULL,
                room_number VARCHAR(10) NOT NULL,
                notes TEXT,
                order_status ENUM('Pending', 'In Progress', 'Completed', 'Cancelled') NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE
            )";
            $this->connection->query($orders);
            

            $order_details = "CREATE TABLE IF NOT EXISTS order_details (
                order_detail_id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                product_id INT NOT NULL,
                quantity INT NOT NULL,
                price DECIMAL(10, 2) NOT NULL,
                FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
            )";
            $this->connection->query($order_details);

            $cart_table = "CREATE TABLE IF NOT EXISTS cart (
                cart_id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                product_id INT NOT NULL,
                quantity INT NOT NULL,
                price DECIMAL(10, 2) NOT NULL,
                FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
            )";
            $this->connection->query($cart_table);
            
            
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }}
/////////////////////////////functions
    function get_connection()
    {
        return $this->connection;
    }
    function get_data($table, $condition = "", $params = array())
    {
        $query = "SELECT * FROM $table";
        if (!empty($condition)) {
            $query .= " WHERE $condition";
        }
        
        $statement = $this->connection->prepare($query);
        $statement->execute($params);
        
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
     function insert_data($table, $cols, $values) {
        try {
            $valuesch = implode(', ', array_fill(0, count($values), '?'));
            $query = "INSERT INTO $table ($cols) VALUES ($valuesch)";
            $statement = $this->connection->prepare($query);
            $statement->execute($values);
            return true;
        } catch (PDOException $e) {
            // die("Execution failed: " . $e->getMessage());
                        header("location:Register.php");
                        

        }
        
    }

    function getData_UseEmail($table, $cols, $email) {
        try {
            $valuesch = implode(', ', array_fill(0, count($email), '?'));   
            $query = "SELECT $cols FROM $table WHERE email IN ($valuesch)";
            $statement = $this->connection->prepare($query);
            $statement->execute($email);

            return $statement;
        } catch (PDOException $e) {

            // die("Execution failed: " . $e->getMessage());
            header("location:Register.php?err=" . json_encode($e->getMessage()));

        }
    }




}

$db = new db(); 
echo "Connected";
?>