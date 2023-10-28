<?php
session_start();

// End session
if(!isset($_SESSION['userlogin'])) {
  header('Location: login.php');
  exit();
}

// Include database connection
include"config/db.php";

// Select all data
$query = "SELECT * FROM customer";

// Prepare statement
$stmt = $con->prepare($query);

// Execute
$stmt->execute();

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <title>Data Table Assignment</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-3">
  <h3>
    Browsing data as
    <?php
if (isset($_SESSION['userlogin']) && isset($_SESSION['userRole'])) 
{
    // Display role first, then username
    echo $_SESSION['userRole'] . " " . $_SESSION['userlogin'] . "";
}
else 
{
    echo "Session variables are not set!";
}
?>

  </h3>

    <?php
      if (isset($_GET['deleted']) && $_GET['deleted'] == 'true') 
      {
          echo "<div id='success-alert' class='alert alert-success d-flex justify-content-between align-items-center ms-auto' style='max-width: 400px;'>Record was successfully deleted.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";

      }
    ?>

  <p></p>            
  <table class="table table-bordered">
    <thead>

        <tr>
           <th>CUSTOMER ID</th>
           <th>STORE ID</th>
           <th>FIRST NAME</th>
           <th>LAST NAME</th>
           <th>EMAIL</th>
           <th>Action</th>
        </tr>


      <tbody>
        <?php 
          while ($row = $stmt->fetch(PDO :: FETCH_ASSOC)) 
          {

            extract($row);
            echo"<tr>";
            echo"<td>{$customer_id}</td>";
            echo"<td>{$store_id}</td>";
            echo"<td>{$first_name}</td>";
            echo"<td>{$last_name}</td>";
            echo"<td>{$email}</td>";
            echo "<td>";
            // View button (Available for all roles)
echo "<a href='view.php?customer_id={$customer_id}' class='btn btn-info btn-sm'>View</a>";
echo " ";

// Edit button (Available for admin and super admin)
if ($_SESSION['userRole'] == 'Admin' || $_SESSION['userRole'] == 'SuperAdmin') {
    echo "<a href='edit.php?customer_id={$customer_id}' class='btn btn-primary btn-sm'>Edit</a>";
} else {
    echo "<a href='#' class='btn btn-secondary btn-sm' disabled>Edit</a>";
}
echo " ";

// Delete button (Available for super admin only)
if ($_SESSION['userRole'] == 'SuperAdmin') {
    echo "<a href='delete.php?customer_id={$customer_id}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure?')\">Delete</a>";
} else {
    echo "<a href='#' class='btn btn-secondary btn-sm' disabled>Delete</a>";
}


          }

        ?>
     </tbody>
  </table>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/datatables.min.js"></script>
<script>
$(document).ready(function() {
  // Check if DataTable is initialized
  if ( $.fn.DataTable.isDataTable('table') ) {
    $('table').DataTable().destroy();
  }

  var table = $('table').DataTable({
    order: [0, 'desc']
  });

// Adding Logout Button next to Search Box
// Adding Logout and Add Buttons next to Search Box
$(".dataTables_filter").append('<a href="add.php" class="btn btn-primary" style="margin-left:10px;">Add</a>');
$(".dataTables_filter").append('<a href="welcome.php" class="btn btn-warning" style="margin-left:10px;">Back</a>');
$(".dataTables_filter").append('<a href="logout.php" class="btn btn-danger" style="margin-left:10px;">Logout</a>');

});


</script>
<script>
  // Use setTimeout to execute code after a certain time has passed
  setTimeout(function() {
    // Find the element with id='success-alert' and remove it
    const el = document.getElementById('success-alert');
    if(el) {
      el.remove();
    }
  }, 4000); // 5000 milliseconds (or 5 seconds)
</script>

</body>
</html>
