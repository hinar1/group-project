<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'employer'){
    header("Location: ../index.php");
    exit;
}

$employer_id = $_SESSION['uid'];

// Get company_id of employer
$comp_res = mysqli_query($conn, "SELECT company_id FROM employer WHERE user_id=$employer_id");
$comp = mysqli_fetch_assoc($comp_res);
$company_id = $comp['company_id'];

$msg = "";

// Handle posting creation
if(isset($_POST['create'])){
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $min_gpa = $_POST['min_gpa'];
    $location = $_POST['location'];
    $stipend = $_POST['stipend'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $deadline = $_POST['application_deadline'];

    mysqli_query($conn, "
        INSERT INTO internship_posting(company_id, title, description, min_gpa, location, stipend, start_date, end_date, application_deadline)
        VALUES($company_id, '$title', '$desc', $min_gpa, '$location', $stipend, '$start_date', '$end_date', '$deadline')
    ");

    $msg = "✅ Internship posted successfully!";
}
?>

<link rel="stylesheet" href="style.css">
<div class="container">
<h2>Create Internship Posting</h2>
<?php if($msg) echo "<p>$msg</p>"; ?>

<form method="POST">
Title: <input name="title" required><br>
Description: <textarea name="description" required></textarea><br>
Min GPA: <input type="number" step="0.01" name="min_gpa" required><br>
Location: <input name="location" required><br>
Stipend: <input type="number" name="stipend" required><br>
Start Date: <input type="date" name="start_date" required><br>
End Date: <input type="date" name="end_date" required><br>
Application Deadline: <input type="date" name="application_deadline" required><br>
<button name="create">Create</button>
</form>

<a class="button" href="dashboard.php">Back to Dashboard</a>
</div>
