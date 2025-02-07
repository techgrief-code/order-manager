<?php
include 'mysqlconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $senditPrice = $_POST["senditPrice"];

    $sql = "UPDATE products SET senditPrice = '$senditPrice' WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        session_start();
        $_SESSION['error_message'] = "Error updating record: " . $conn->error;
        header("Location: index.php");
        exit();
    }
}

$conn->close();
?>
