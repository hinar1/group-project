<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['uid']) || $_SESSION['role'] != 'student'){
    header("Location: ../auth/login.php");
    exit;
}

$student_id = $_SESSION['uid'];

// Fetch AI recommendations
$rec_query_sql = "
SELECT ir.score, ir.explanation, ip.title, ip.location, ip.stipend, ip.posting_id
FROM ai_recommendation ir
JOIN internship_posting ip ON ir.posting_id = ip.posting_id
WHERE ir.student_id = $student_id
ORDER BY ir.score DESC
";

$rec_query = mysqli_query($conn, $rec_query_sql);

if(!$rec_query){
    die("SQL Error: ".mysqli_error($conn)."<br>Query: ".$rec_query_sql);
}
?>
<link rel="stylesheet" href="../style.css">
<div class="container">
<h2>AI Recommended Internships</h2>
<table>
<tr>
<th>Title</th>
<th>Location</th>
<th>Stipend</th>
<th>Matching Score</th>
<th>Explanation</th>
<th>Action</th>
</tr>
<?php
if(mysqli_num_rows($rec_query) > 0){
    while($rec = mysqli_fetch_assoc($rec_query)){
        echo "<tr>
            <td>{$rec['title']}</td>
            <td>{$rec['location']}</td>
            <td>{$rec['stipend']}</td>
            <td>{$rec['score']}</td>
            <td>{$rec['explanation']}</td>
            <td><a href='apply.php?id={$rec['posting_id']}'>Apply</a></td>
        </tr>";
    }
}else{
    echo "<tr><td colspan='6'>No recommendations found yet.</td></tr>";
}
?>
</table>
<a href="dashboard.php">Back to Dashboard</a>
</div>
