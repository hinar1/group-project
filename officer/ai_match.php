<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'officer'){
    header("Location: ../index.php");
    exit;
}

// Fetch AI matches
$ai_matches = mysqli_query($conn, "
    SELECT u.first_name, u.last_name, ip.title, ir.score, ir.explanation
    FROM ai_recommendation ir
    JOIN student s ON ir.student_id = s.user_id
    JOIN user u ON s.user_id = u.user_id
    JOIN internship_posting ip ON ir.posting_id = ip.posting_id
    ORDER BY ir.score DESC
");
?>

<link rel="stylesheet" href="style.css">
<div class="container">
<h2>AI Match View</h2>

<table>
<tr><th>Student</th><th>Internship</th><th>Matching Score</th><th>Explanation</th></tr>
<?php
if(mysqli_num_rows($ai_matches) > 0){
    while($row = mysqli_fetch_assoc($ai_matches)){
        echo "<tr>
            <td>{$row['first_name']} {$row['last_name']}</td>
            <td>{$row['title']}</td>
            <td>{$row['score']}</td>
            <td>{$row['explanation']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No AI recommendations yet.</td></tr>";
}
?>
</table>

<a class="button" href="dashboard.php">Back to Dashboard</a>
</div>
