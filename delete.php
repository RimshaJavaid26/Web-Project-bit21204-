<?php
include 'connect.php';
session_start();

// Only admins should access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "Access denied. Admins only.";
    exit();
}

// Get the product ID from the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the SQL statement to delete the product securely
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);

    // Execute the query
    if ($stmt->execute()) {
        echo "Product deleted successfully!";
        header('Location: admin.php');
        exit(); // Prevent further script execution after redirection
    } else {
        echo "Error deleting product: " . $conn->error;
    }

    // Close the prepared statement
    $stmt->close();
} else {
    echo "No product ID provided.";
}

// Close the database connection
$conn->close();
?>
