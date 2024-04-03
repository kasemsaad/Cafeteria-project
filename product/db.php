<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


class db {
      private $host="localhost";
      private $dbname="cafeteria";
      private $user="root";
      private $pass = "";
      private $connection=""; 

      function __construct(){
        $this->connection= new pdo("mysql:host=$this->host;dbname=$this->dbname",$this->user,$this->pass);
      }

      function get_connection(){
        return $this->connection;
      }

      function get_data($table,$condition=1){
        return $this->connection->query("select * from $table where $condition ");
      }

      function delete_data($tablename,$cond){
        $query = "DELETE FROM $tablename WHERE $cond";
        $this->connection->query($query); 
           }

      function insert_data($table_name,$col,$values){
        $this->connection->query("insert into $table_name ($col) values ($values)");
      }

      public function update_data($table_name, $data, $condition) {
   
        $set_values = [];
        foreach ($data as $key => $value) {
            $set_values[] = "$key = '$value'";
        }
        $set_values_str = implode(', ', $set_values);
        $query = "UPDATE $table_name SET $set_values_str WHERE $condition";
        $result = $this->connection->query($query);
    
        if (!$result) {
            throw new PDOException("Error executing : " . $this->connection->errorInfo()[2]);
        }
        var_dump($result);
        return $result;
    }
}

?>