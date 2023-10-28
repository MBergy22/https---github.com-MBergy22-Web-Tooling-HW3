<?php
include('config/db.php');

if (!empty($_POST['userName'])) {
    $userName = $_POST['userName'];
    $sql = "SELECT UserName FROM userdata WHERE UserName=?";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(1, $userName, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($stmt->rowCount() > 0) 
    {
        echo "<span style='color:red'>Username already exists.</span>";
    } 

    else 	
    {
        echo "<span style='color:green'>Username available.</span>";
    }
}
?>