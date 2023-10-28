<?php
// Include database connection
include "config/db.php";

$first_name = $last_name = $email = $store_id = "";

if ($_POST) {
    $store_id = isset($_POST['store_id']) ? (int)$_POST['store_id'] : 0;
    $first_name = sanitize_input($_POST['first_name']);
    $last_name = sanitize_input($_POST['last_name']);
    $email = sanitize_input($_POST['email']);

    // Validating
    $store_id = isset($_POST['store_id']) ? (int)$_POST['store_id'] : 0;
        if ($store_id < 1 || $store_id > 5) {
            $errors[] = "Store ID must be a number between 1 and 5";
        }
        if (!preg_match("/^[a-zA-Z]*$/", $first_name) || !preg_match("/^[a-zA-Z]*$/", $last_name)) {
                $errors[] = "Names must only contain letters";
            }
    $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          echo "<div class='alert alert-danger'>Invalid email format.</div>";
        }

    if (empty($first_name) || empty($last_name) || empty($email) || empty($store_id)) {
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                All fields must be filled out.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
            } else {

        // If validation is successful, proceed to insert into database
            try {
                $query = "INSERT INTO customer (first_name, last_name, email, store_id) VALUES (?, ?, ?, ?)";
                $stmt = $con->prepare($query);
                $stmt->bindParam(1, $first_name);
                $stmt->bindParam(2, $last_name);
                $stmt->bindParam(3, $email);
                $stmt->bindParam(4, $store_id);

            if ($stmt->execute()) 
            {
                if ($stmt->rowCount() > 0) 
                {
                    echo "<div id='success-alert' class='alert alert-success alert-dismissible fade show' role='alert'>
                            Record was added.
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                          </div>";
                } 
                    else 
                {
                    echo "<div id='warning-alert' class='alert alert-warning alert-dismissible fade show' role='alert'>
                            Query executed, but no record was added.
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                          </div>";
                }
            } 
                else 
            {
                echo "<div id='danger-alert' class='alert alert-danger alert-dismissible fade show' role='alert'>
                        Unable to add the record.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                      </div>";
            }
        } 
            catch (PDOException $e) 
        {
            echo "Error: " . $e->getMessage();
        }
    }
}

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-3">
    <form action="add.php" method="POST">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td>Store ID</td>
                    <td><input type="number" min="1" max="5" class="form-control" id="store_id" placeholder="Enter Store ID" name="store_id"></td>
                </tr>

                <tr>
                    <td>First name</td>
                    <td><input type="text" class="form-control" id="first_name" placeholder="Enter Name" name="first_name"></td>
                </tr>

                <tr>
                    <td>Last name</td>
                    <td><input type="text" class="form-control" id="last_name" placeholder="Enter Name" name="last_name"></td>
                </tr>

                <tr>
                    <td>Email</td>
                    <td><input type="email" class="form-control" id="email" placeholder="Enter Email" name="email"></td>
                </tr>
            </tbody>
        </table>
        <div class="mt-2 text-center"> <!-- Added 'text-center' here -->
          <input type="submit" name="submit" value="Confirm Addition" class="btn btn-success btn-block">
          <a href='index.php'><button type='button' class='btn btn-warning btn-space'>Back <i class='fas fa-arrow-left'></i></button></a>        
        </div>
        <script>
  // Use setTimeout to execute code after a certain time has passed
  setTimeout(function() {
    // Find elements by their ids and remove them
    const successEl = document.getElementById('success-alert');
    const warningEl = document.getElementById('warning-alert');
    const dangerEl = document.getElementById('danger-alert');
    
    if(successEl) { successEl.remove(); }
    if(warningEl) { warningEl.remove(); }
    if(dangerEl) { dangerEl.remove(); }
  }, 4000); // 5000 milliseconds (or 5 seconds)
</script>

