<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'officer'){
    header("Location: ../index.php");
    exit;
}

// Fetch all postings
$postings = mysqli_query($conn, "SELECT ip.*, c.name AS company_name FROM internship_posting ip JOIN company c ON ip.company_id=c.company_id");
?>

<link rel="stylesheet" href="style.css">
<div class="container">
<h2>Manage Internship Postings</h2>

<table>
<tr><th>Title</th><th>Company</th><th>Location</th><th>Min GPA</th></tr>
<?php
while($p = mysqli_fetch_assoc($postings)){
    echo "<tr>
        <td>{$p['title']}</td>
        <td>{$p['company_name']}</td>
        <td>{$p['location']}</td>
        <td>{$p['min_gpa']}</td>
    </tr>";
}
?>
</table>

<a class="button" href="dashboard.php">Back to Dashboard</a>
</div>
