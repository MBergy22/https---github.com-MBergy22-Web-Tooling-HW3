<?php

// include database connection

include"config/db.php";

$customer_id = isset($_GET['customer_id']) ? (int)$_GET['customer_id'] : die('ERROR: Record ID not found.');
$email = "";
$first_name = "";
$last_name = "";
$store_id = isset($_POST['store_id']) ? (int)$_POST['store_id'] : 0;

$errors = [];

if ($_POST) {
    try {
        // Input validation
        $store_id = isset($_POST['store_id']) ? (int)$_POST['store_id'] : 0;
        if ($store_id < 1 || $store_id > 5) {
            $errors[] = "Store ID must be a number between 1 and 5";
        }

        $first_name = sanitize_input($_POST['first_name']);
        $last_name = sanitize_input($_POST['last_name']);
        
        if (!preg_match("/^[a-zA-Z]*$/", $first_name) || !preg_match("/^[a-zA-Z]*$/", $last_name)) {
            $errors[] = "Names must only contain letters";
        }

        $email = sanitize_input($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }

        if (empty($first_name) || empty($last_name) || empty($email) || empty($store_id)) {
            $errors[] = "All fields must be filled out";
        }

        // If errors exist, do not proceed with updating the database
        if (!empty($errors)) {
foreach ($errors as $index => $error) {
echo "<div class='alert alert-danger alert-dismissible fade show custom-alert' role='alert' id='alert-danger-{$index}'>";
    echo "{$error}";
    echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
    echo "</div>";
}

        } else {
            // Prepare SQL
            $query = "UPDATE customer SET first_name=?, last_name=?, email=?, store_id=? WHERE customer_id=?";
            $stmt = $con->prepare($query);

            // Bind parameters
            $stmt->bindParam(1, $first_name);
            $stmt->bindParam(2, $last_name);
            $stmt->bindParam(3, $email);
            $stmt->bindParam(4, $store_id);
            $stmt->bindParam(5, $customer_id);

    // execute query

            if ($stmt->execute()) {
echo "<div class='alert alert-success alert-dismissible fade show custom-alert' role='alert' id='alert-success'>";
    echo "Record was updated.";
    echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
    echo "</div>";
} 
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

try
{
  // prepare select query
  $query = "SELECT first_name, last_name, email, store_id FROM customer WHERE customer_id=? LIMIT 0,1";

  $stmt = $con->prepare($query);
  $stmt->bindParam(1,$customer_id);
  $stmt->execute();

  // store retrieved row in variable
  $row = $stmt -> fetch(PDO::FETCH_ASSOC);

  // fill data
  // print_r($row);
  $first_name = $row['first_name'];
  $last_name = $row['last_name'];
  $email = $row['email'];
  $store_id = $row['store_id'];


}

catch(PDOException $e)
{
  echo "Error : ".$e->getMessage(); 
}

function sanitize_input($data)
{
  // code
  $data=trim($data);
  $data=stripslashes($data);
  $data=htmlspecialchars($data);
  return $data;
}


?>


<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
	<title>PHP & Mysql Edit</title>
</head>

<style type="text/css">
	.mt-n1 {
		margin-top: -0.25rem !important;
	}
.error {
  color: #FF0000;
}



</style>
<body>
<div class="container mt-3">

<h3></h3>


  <table class="table table-bordered">

    <tbody>
<form action="edit.php?customer_id=<?php echo htmlspecialchars($customer_id); ?>" method="POST">

      <tr>
        <td>Store ID</td>
        <td><input type="text" class="form-control" id="store_id" placeholder="Enter Store Number" name="store_id" value="<?php echo $store_id; ?>" ><span class="error"></span></td>
      </tr>

      <tr>
        <td>First name</td>
        <td><input type="text" class="form-control" id="first_name" placeholder="Enter Name" name="first_name" value="<?php echo $first_name; ?>" ><span class="error"> <?php // echo $fnameError; ?></span></td>
      </tr>      

      <tr>
        <td>Last name</td>
        <td><input type="text" class="form-control" id="last_name" placeholder="Enter Name" name="last_name" value="<?php echo $last_name; ?>" ><span class="error"> <?php // echo $lnameError; ?></span></td>
      </tr>  

      <tr>
        <td>Email</td>
        <td><input type="text" class="form-control" id="email" placeholder="Enter Email" name="email" value="<?php echo $email; ?>" ><span class="error"> <?php  // echo $emailError; ?></span></td>
      </tr>

    </tbody>
  </table>

          <div class="mt-2 text-center"> 
          <input type="submit" name="submit" value="Confirm Edit" class="btn btn-success btn-block">
          <a href='index.php'><button type='button' class='btn btn-primary btn-space'>Back <i class='fas fa-arrow-left'></i></button></a>        
        </div>

</form>


</div>

</body>
<script>
    setTimeout(function() {
        const alertEls = document.querySelectorAll('.custom-alert');
        alertEls.forEach(el => el.remove());
    }, 4000); // 4000 milliseconds (or 4 seconds)
</script>

</html>