<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'student'){
    header("Location: ../index.php");
    exit;
}

$student_id = $_SESSION['uid'];

// Fetch student name
$res = mysqli_query($conn, "SELECT first_name,last_name FROM user WHERE user_id=$student_id");
$user = mysqli_fetch_assoc($res);
?>

<link rel="stylesheet" href="style.css">
<div class="container">
<h2>Welcome, <?php echo $user['first_name']; ?></h2>

<h3>AI Recommended Internships:</h3>
<?php
$ai_res = mysqli_query($conn, "
    SELECT ir.score, ir.explanation, ip.title, ip.location, ip.stipend, ip.posting_id 
    FROM ai_recommendation ir 
    JOIN internship_posting ip ON ir.posting_id = ip.posting_id 
    WHERE ir.student_id = $student_id 
    ORDER BY ir.score DESC
");

if(mysqli_num_rows($ai_res) > 0){
    echo "<table><tr><th>Title</th><th>Location</th><th>Stipend</th><th>Matching Score</th><th>Explanation</th><th>Action</th></tr>";
    while($row = mysqli_fetch_assoc($ai_res)){
        echo "<tr>
            <td>{$row['title']}</td>
            <td>{$row['location']}</td>
            <td>{$row['stipend']}</td>
            <td>{$row['score']}</td>
            <td>{$row['explanation']}</td>
            <td><a class='button' href='apply.php?id={$row['posting_id']}'>Apply</a></td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No recommendations yet.</p>";
}
?>

<a class="button" href="manage_skills.php">Manage Skills</a>
<a class="button" href="upload_resume.php">Upload Resume</a>
<a class="button" href="outcomes.php">View Outcomes</a>
<a class="button" href="../auth/logout.php">Logout</a>
</div>
