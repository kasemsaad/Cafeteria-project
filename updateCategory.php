<?php
require("db.php");
$db = new db();

if(isset($_POST['id'])) { 
    $id = $_POST['id'];
} else {
    echo "No ID provided.";
    exit;
}

$data = array(
    'category_id'=>$_POST['category_id'],
    'category_name' => $_POST['category_name'],
    'created_at' => $_POST['created_at'], // Assuming these are the correct column names in your database
    'updated_at' => $_POST['updated_at']
);

$condition = "category_id = $id"; // Adjust the condition based on your table structure


try {
    $result = $db->update_data("categories", $data, $condition);

    if ($result) {
        header("location:viewAllCategory.php");
        //echo "Category updated successfully.";
    } else {
        echo "Error updating category.";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>