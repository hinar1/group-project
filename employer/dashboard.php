<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'employer'){
    header("Location: ../index.php");
    exit;
}

$employer_id = $_SESSION['uid'];

// Fetch employer's name
$res = mysqli_query($conn, "SELECT first_name,last_name FROM user WHERE user_id=$employer_id");
$user = mysqli_fetch_assoc($res);
?>

<link rel="stylesheet" href="style.css">
<div class="container">
<h2>Welcome, <?php echo $user['first_name']; ?> (Employer)</h2>

<a class="button" href="create_posting.php">Create Internship Posting</a>
<a class="button" href="view_applications.php">View Applications</a>
<a class="button" href="../auth/logout.php">Logout</a>
</div>
