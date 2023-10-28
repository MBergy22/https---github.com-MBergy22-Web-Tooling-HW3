<?php
// Include database connection
include "config/db.php";

// Check if 'customer_id' is set in URL
if (isset($_GET['customer_id']) && is_numeric($_GET['customer_id']) && $_GET['customer_id'] > 0) {
    $customer_id = $_GET['customer_id'];
} else {
    echo "Invalid Customer ID.";
    exit;
}

try {
    // Prepare SQL query to delete the record
    $query = "DELETE FROM customer WHERE customer_id = :customer_id";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':customer_id', $customer_id);

    // Execute the query
    if ($stmt->execute()) {
        header('Location: index.php?deleted=true'); // Redirect after successful deletion
        exit();
    } else {
        echo "<div class='alert alert-danger'>Unable to delete the record.</div>";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
