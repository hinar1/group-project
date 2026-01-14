<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['uid']) || $_SESSION['role'] != 'employer'){
    header("Location: ../auth/login.php");
    exit;
}

if(isset($_GET['app_id'])){
    $app_id = $_GET['app_id'];
    mysqli_query($conn,"UPDATE application SET status='Shortlisted' WHERE application_id=$app_id");
    $msg = "Candidate shortlisted successfully!";
}
?>
<link rel="stylesheet" href="../style.css">
<div class="container">
<?php if(isset($msg)) echo "<p style='color:green;'>$msg</p>"; ?>
<a href="review_candidates.php">Back to Review Candidates</a>
</div>
