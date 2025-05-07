<?php 
include 'connection.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $sql = "DELETE FROM products Where id= '$id'";

    if($conn->query($sql) === TRUE){
        echo "<script>alert('Product deleted successfully'); window.location.href='admin.php';</script>";
    }else{
        echo "Error: " . $conn->error;
    }


}

?>