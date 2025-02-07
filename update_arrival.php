<?php
include 'mysqlconfig.php';

$id = $_GET["id"];
$arrivalDate = date("Y-m-d");

$sql = "UPDATE products SET arrivalDate = '$arrivalDate' WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    header("Location: index.php");
    exit();
} else {
    session_start();
    $_SESSION['error_message'] = "Error updating record: " . $conn->error;
    header("Location: index.php");
    exit();
}

$conn->close();
?>