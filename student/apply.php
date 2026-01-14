<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'student'){
    header("Location: ../index.php");
    exit;
}

$student_id = $_SESSION['uid'];
$posting_id = $_GET['id'];

// Check if already applied
$check = mysqli_query($conn, "SELECT * FROM application WHERE student_id=$student_id AND posting_id=$posting_id");
if(mysqli_num_rows($check) > 0){
    $msg = "❌ You have already applied to this internship.";
} else {
    // Insert application
    mysqli_query($conn, "
        INSERT INTO application(posting_id, student_id, status, matching_score)
        VALUES($posting_id, $student_id, 'Applied', 70)
    ");
    $msg = "✅ Applied successfully!";
}
?>

<link rel="stylesheet" href="style.css">
<div class="container">
<h2>Apply to Internship</h2>
<p><?php echo $msg; ?></p>
<a class="button" href="dashboard.php">Back to Dashboard</a>
</div>
