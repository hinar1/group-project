<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'officer'){
    header("Location: ../index.php");
    exit;
}

// Fetch all applications
$applications = mysqli_query($conn, "
    SELECT a.application_id, u.first_name, u.last_name, ip.title, a.status, a.matching_score
    FROM application a
    JOIN student s ON a.student_id=s.user_id
    JOIN user u ON s.user_id=u.user_id
    JOIN internship_posting ip ON a.posting_id=ip.posting_id
    ORDER BY a.status
");
?>

<link rel="stylesheet" href="style.css">
<div class="container">
<h2>All Applications</h2>

<table>
<tr><th>Student</th><th>Internship</th><th>Status</th><th>Matching Score</th></tr>
<?php
while($app = mysqli_fetch_assoc($applications)){
    echo "<tr>
        <td>{$app['first_name']} {$app['last_name']}</td>
        <td>{$app['title']}</td>
        <td>{$app['status']}</td>
        <td>{$app['matching_score']}</td>
    </tr>";
}
?>
</table>

<a class="button" href="dashboard.php">Back to Dashboard</a>
</div>
