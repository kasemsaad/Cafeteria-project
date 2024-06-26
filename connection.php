<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
$err = [];
if (isset($_GET['err'])) {
    $err = json_decode($_GET['err'], true);
}

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

                name VARCHAR(50) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role ENUM('User', 'Admin') NOT NULL,
                room_no INT,
                ext INT,
                profile_image VARCHAR(255),
                resetcode INT,
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

            $stmt = $this->connection->query("CREATE database if not exists {$this->database}");
            $stmt->execute();

            $this->connection->query("USE {$this->database}");

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
        }
    }
  
  
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
    function get_dataone($table, $condition = " ")
    {
        $query = "SELECT * FROM $table";
        if (!empty($condition)) {
            $query .= " WHERE $condition";
        }
        $statement = $this->connection->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
      function ext()
    {
        $query = "SELECT  DISTINCT ext FROM rooms";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    function insert_data($table, $cols, $values)
    {
        try {
            $valuesch = implode(', ', array_fill(0, count($values), '?'));
            $query = "INSERT INTO $table ($cols) VALUES ($valuesch)";
            $statement = $this->connection->prepare($query);
            $statement->execute($values);
            return true;
        } catch (PDOException $e) {
            header("location:index.php?error=insert_failed");
            exit;
        }
    }

    function getData_UseEmail($table, $cols, $email)
    {
        try {
            $valuesch = implode(', ', array_fill(0, count($email), '?'));
            $query = "SELECT $cols FROM $table WHERE email IN ($valuesch)";
            $statement = $this->connection->prepare($query);
            $statement->execute($email);

            return $statement;
        } catch (PDOException $e) {
            header("location:addUser.php?err=" . json_encode($e->getMessage()));
            exit;

        }
    }

    function delete_data($table, $cond)
    {
        try {
            $query = "DELETE FROM $table WHERE $cond";
            $statement = $this->connection->prepare($query);
            $statement->execute();
            return $statement->rowCount();
        } catch (PDOException $e) {
            error_log('Error deleting data: ' . $e->getMessage());
            header("location:viewAllUsers.php?err=" . json_encode($e->getMessage()));
            exit;
        }


    }

    function update_data($table, $cols, $condition)
    {
        try {
            $query = "UPDATE $table SET $cols WHERE $condition";
            $statement = $this->connection->prepare($query);
            return $statement->execute();
        } catch (PDOException $e) {
            header("location:viewAllUsers.php?err=" . urlencode($e->getMessage()));
            // die ("Connection failed: " . $e->getMessage());
            exit;
        }
    }


}

?>
