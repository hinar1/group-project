<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['uid']) || $_SESSION['role'] != 'employer'){
    header("Location: ../auth/login.php");
    exit;
}

$employer_id = $_SESSION['uid'];

// Get company_id of this employer
$company = mysqli_query($conn,"SELECT company_id FROM employer WHERE user_id=$employer_id");
$company_id = mysqli_fetch_assoc($company)['company_id'];

// Get all postings of this company
$postings = mysqli_query($conn,"SELECT * FROM internship_posting WHERE company_id=$company_id");
?>
<link rel="stylesheet" href="../style.css">
<div class="container">
<h2>Review Candidates</h2>

<?php
while($p = mysqli_fetch_assoc($postings)){
    echo "<h3>{$p['title']}</h3>";
    $applications = mysqli_query($conn,"SELECT a.*, s.first_name, s.last_name, ir.score, ir.explanation
                                        FROM application a
                                        JOIN student s ON a.student_id=s.user_id
                                        LEFT JOIN ai_recommendation ir ON ir.student_id=s.user_id AND ir.posting_id=a.posting_id
                                        WHERE a.posting_id={$p['posting_id']}");
    if(mysqli_num_rows($applications) > 0){
        echo "<table>
                <tr>
                    <th>Student</th>
                    <th>Status</th>
                    <th>AI Score</th>
                    <th>Explanation</th>
                    <th>Action</th>
                </tr>";
        while($a = mysqli_fetch_assoc($applications)){
            echo "<tr>
                    <td>{$a['first_name']} {$a['last_name']}</td>
                    <td>{$a['status']}</td>
                    <td>{$a['score']}</td>
                    <td>{$a['explanation']}</td>
                    <td><a href='finalize_intern.php?app_id={$a['application_id']}'>Shortlist / Finalize</a></td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No candidates applied yet.</p>";
    }
}
?>
<a href="dashboard.php">Back to Dashboard</a>
</div>
