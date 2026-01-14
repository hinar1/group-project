<?php
session_start();
if(isset($_SESSION['role'])){
    header("Location: ".$_SESSION['role']."/dashboard.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Internship Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h2>AI-Powered Internship Management System</h2>
<a href="auth/login.php">Login</a> |
<a href="auth/register.php">Register</a>
</div>
</body>
</html>
