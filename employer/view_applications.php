<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['uid']) || $_SESSION['role'] !== 'employer') {
    header("Location: ../index.php");
    exit;
}

$uid = $_SESSION['uid'];

/* Get company_id */
$q = mysqli_query($conn, "SELECT company_id FROM employer WHERE user_id = $uid");
if (!$q) die(mysqli_error($conn));

$row = mysqli_fetch_assoc($q);
if (!$row || empty($row['company_id'])) {
    die("Employer not linked to any company.");
}

$company_id = (int)$row['company_id'];

/* FINAL SAFE QUERY */
$sql = "
SELECT 
    u.first_name,
    u.last_name,
    ip.title,
    a.status,
    a.matching_score
FROM application a
JOIN internship_posting ip ON a.posting_id = ip.posting_id
JOIN student s ON a.student_id = s.user_id
JOIN user u ON u.user_id = s.user_id
WHERE ip.company_id = $company_id
ORDER BY a.application_id DESC
";

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("SQL ERROR:<br>" . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
<title>View Applications</title>
<style>
body { font-family: Arial; background:#f4f6f9; padding:20px; }
table { width:100%; border-collapse:collapse; background:#fff; }
th, td { padding:10px; border:1px solid #ccc; }
th { background:#2c3e50; color:white; }
</style>
</head>
<body>

<h2>Student Applications</h2>
<a href="dashboard.php">← Back</a><br><br>

<table>
<tr>
<th>Student</th>
<th>Internship</th>
<th>Status</th>
<th>Score</th>
</tr>

<?php
if (mysqli_num_rows($result) > 0) {
    while ($r = mysqli_fetch_assoc($result)) {
        echo "<tr>
            <td>{$r['first_name']} {$r['last_name']}</td>
            <td>{$r['title']}</td>
            <td>{$r['status']}</td>
            <td>{$r['matching_score']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No applications found</td></tr>";
}
?>

</table>
</body>
</html>
