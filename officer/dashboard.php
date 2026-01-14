<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'officer'){
    header("Location: ../index.php");
    exit;
}

$officer_id = $_SESSION['uid'];

// Get officer name
$res = mysqli_query($conn, "SELECT first_name,last_name FROM user WHERE user_id=$officer_id");
$user = mysqli_fetch_assoc($res);
?>

<link rel="stylesheet" href="style.css">
<div class="container">
<h2>Welcome, <?php echo $user['first_name']; ?> (Placement Officer)</h2>

<a class="button" href="manage_postings.php">Manage Internship Postings</a>
<a class="button" href="view_applications.php">View All Applications</a>
<a class="button" href="ai_match.php">AI Match View</a>
<a class="button" href="../auth/logout.php">Logout</a>
</div>
