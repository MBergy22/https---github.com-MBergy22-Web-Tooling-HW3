<?php
include('config/db.php');

if (!empty($_POST['userEmail'])) {
    $userEmail = $_POST['userEmail'];
    $sql = "SELECT UserEmail FROM userdata WHERE UserEmail=?";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(1, $userEmail, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($stmt->rowCount() > 0) 
    {
        echo "<span style='color:red'>Email already exists.</span>";
    } 
    
    else 	
    {
        echo "<span style='color:green'>Email available.</span>";
    }
}
?>
