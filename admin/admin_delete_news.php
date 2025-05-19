<?php
include '../config.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM news WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('News deleted successfully!'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>
