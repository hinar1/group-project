<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['uid']) || $_SESSION['role'] != 'officer'){
    header("Location: ../auth/login.php");
    exit;
}

// Example report: Number of applications per posting
$report = mysqli_query($conn,"
SELECT ip.title, COUNT(a.application_id) as total_applications
FROM internship_posting ip
LEFT JOIN application a ON ip.posting_id = a.posting_id
GROUP BY ip.posting_id
");
?>
<link rel="stylesheet" href="../style.css">
<div class="container">
<h2>Reports & Analytics</h2>
<h3>Applications per Posting</h3>
<table>
<tr>
    <th>Internship</th>
    <th>Total Applications</th>
</tr>
<?php
while($r = mysqli_fetch_assoc($report)){
    echo "<tr>
            <td>{$r['title']}</td>
            <td>{$r['total_applications']}</td>
          </tr>";
}
?>
</table>
<a href="dashboard.php">Back to Dashboard</a>
</div>
