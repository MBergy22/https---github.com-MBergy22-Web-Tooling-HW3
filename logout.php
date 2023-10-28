<?php
session_start();
session_destroy(); // This will destroy the session
header("Location: login.php");
exit();
?>
