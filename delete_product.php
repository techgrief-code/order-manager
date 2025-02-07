<?php
include 'mysqlconfig.php';

$id = $_GET["id"];

$sql = "DELETE FROM products WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    header("Location: index.php");
    exit();
} else {
    session_start();
    $_SESSION['error_message'] = "Error deleting record: " . $conn->error;
    header("Location: index.php");
    exit();
}

$conn->close();
?>
