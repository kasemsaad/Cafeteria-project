<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
//-------------------------------------------------------------------//
//---------------------Class Data Base -----------------------------//
//-----------------------------------------------------------------//
class DataBase
{
    private $server = "localhost";
    private $username = "root"; 
    private $password = "";
    private $database = "Cafeteria";
    private $connection;

    function __construct(){
        try {
            $dsn = "mysql:host={$this->server}";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $this->connection->query("CREATE database if not exists {$this->database}");
            $stmt->execute();
            $this->connection->exec("USE {$this->database}");

//-------------------------------------------------------------------//
//--------------------------   Schema  -----------------------------//
//-----------------------------------------------------------------//
                    
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
                email VARCHAR(100) NOT NULL,
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
                category_name VARCHAR(100) NOT NULL UNIQUE,
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
                order_date DATETIME NOT NULL,
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
                order_id INT NOT NULL,
                product_id INT NOT NULL,
                quantity INT NOT NULL,
                price DECIMAL(10, 2) NOT NULL,
                PRIMARY KEY (order_id, product_id),
                FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
            )";
            $this->connection->query($order_details);

         
            
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }}
/////////////////////////////functions
    function get_connection()
    {
        return $this->connection;
    }
     function  insert_data($table, $cols, $values) {
        try {
            $valuesch = implode(', ', array_fill(0, count($values), '?'));
            $query = "INSERT INTO $table ($cols) VALUES ($valuesch)";
            $statement = $this->connection->prepare($query);
            $statement->execute($values);
            return true;
        } catch (PDOException $e) {
            die("Execution failed: " . $e->getMessage());
        }
    }

}
$db = new DataBase(); 
echo "Connected";
?>