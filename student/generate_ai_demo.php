<?php
include("../config/db.php");

// Clear old recommendations
mysqli_query($conn,"DELETE FROM ai_recommendation");

$students = mysqli_query($conn,"SELECT * FROM student");
$postings = mysqli_query($conn,"SELECT * FROM internship_posting");

while($s = mysqli_fetch_assoc($students)){
    $student_id = $s['user_id'];

    while($p = mysqli_fetch_assoc($postings)){
        $posting_id = $p['posting_id'];

        // Basic skill matching logic: random score + bonus if GPA matches
        $base_score = rand(50, 80);

        if($s['gpa'] >= $p['min_gpa']){
            $base_score += 10;
        }

        // Extra bonus if student's skills match posting skills
        $skills = mysqli_query($conn,"SELECT ss.proficiency_level, s.name 
                                     FROM student_skill ss 
                                     JOIN skill s ON ss.skill_id = s.skill_id");

        $bonus = 0;
        while($sk = mysqli_fetch_assoc($skills)){
            if(strpos(strtolower($p['title']), strtolower($sk['name'])) !== false){
                $bonus += $sk['proficiency_level'] * 3; // weight skill level
            }
        }

        $score = min($base_score + $bonus, 100); // max 100

        $explanation = "Recommended based on GPA and skill match.";

        mysqli_query($conn,"INSERT INTO ai_recommendation(student_id,posting_id,score,explanation)
                           VALUES($student_id,$posting_id,$score,'$explanation')");
    }
}

echo "✅ AI Recommendations generated successfully!";
?>
