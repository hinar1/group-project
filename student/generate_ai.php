<?php
include("../config/db.php");

// Generate sample AI recommendations for all students
$students = mysqli_query($conn,"SELECT * FROM student");
$postings = mysqli_query($conn,"SELECT * FROM internship_posting");

while($s = mysqli_fetch_assoc($students)){
    while($p = mysqli_fetch_assoc($postings)){
        $score = rand(60, 100);
        $explanation = "Recommended based on skills match and GPA.";
        mysqli_query($conn,"INSERT INTO ai_recommendation(student_id,posting_id,score,explanation) 
                           VALUES({$s['user_id']},{$p['posting_id']},$score,'$explanation')");
    }
}
echo "✅ AI Recommendations generated!";
?>
