<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'student'){
    header("Location: ../index.php");
    exit;
}

$student_id = $_SESSION['uid'];
$msg = "";

// Handle file upload
if(isset($_POST['upload'])){
    $file_name = $_FILES['resume']['name'];
    $file_tmp = $_FILES['resume']['tmp_name'];
    $target_dir = "../uploads/";
    if(!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    $target_file = $target_dir . basename($file_name);

    if(move_uploaded_file($file_tmp, $target_file)){
        // Save in DB
        $check = mysqli_query($conn, "SELECT * FROM resume WHERE student_id=$student_id");
        if(mysqli_num_rows($check) > 0){
            mysqli_query($conn, "UPDATE resume SET file_url='$target_file' WHERE student_id=$student_id");
        } else {
            mysqli_query($conn, "INSERT INTO resume(student_id, file_url) VALUES($student_id,'$target_file')");
        }
        $msg = "✅ Resume uploaded successfully!";
    } else {
        $msg = "❌ Failed to upload resume.";
    }
}

// Fetch current resume
$res = mysqli_query($conn, "SELECT * FROM resume WHERE student_id=$student_id");
$resume = mysqli_fetch_assoc($res);
?>

<link rel="stylesheet" href="style.css">
<div class="container">
<h2>Upload Resume</h2>
<?php if($msg) echo "<p>$msg</p>"; ?>

<form method="POST" enctype="multipart/form-data">
Choose File:
<input type="file" name="resume" required>
<button name="upload">Upload</button>
</form>

<?php
if($resume){
    echo "<p>Current Resume: <a href='{$resume['file_url']}' target='_blank'>View</a></p>";
}
?>

<a class="button" href="dashboard.php">Back to Dashboard</a>
</div>
