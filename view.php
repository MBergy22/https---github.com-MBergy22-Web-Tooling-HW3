<?php
// Database connection
include "config/db.php";

// Check if customer_id is available in the URL
if (isset($_GET['customer_id']) && is_numeric($_GET['customer_id']) && $_GET['customer_id'] > 0) {
    $customer_id = $_GET['customer_id'];
} else {
    echo "Invalid Customer ID.";
    exit;
}


try {
    // Prepare SQL query to fetch the data
    $query = "SELECT * FROM customer WHERE customer_id = :customer_id";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->execute();

    // Fetch the result
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row) {
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $email = $row['email'];
        $store_id = $row['store_id'];
    } else {
        echo "No data found for this customer ID.";
        exit;
    }

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    Customer Data 
                </div>
                <div class="card-body">
                    <p><strong>Customer ID:</strong> <?php echo htmlspecialchars($customer_id); ?></p>
                    <p><strong>Store ID:</strong> <?php echo htmlspecialchars($store_id); ?></p>
                    <p><strong>First Name:</strong> <?php echo htmlspecialchars($first_name); ?></p>
                    <p><strong>Last Name:</strong> <?php echo htmlspecialchars($last_name); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                </div>
            </div>
        <div class="d-flex justify-content-center">
            <a href='index.php'><button type='button' class='btn btn-primary btn-space'>Back <i class='fas fa-arrow-left'></i></button></a>
        </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
