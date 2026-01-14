<?php
include("db.php");

// Clear previous recommendations
mysqli_query($conn, "TRUNCATE TABLE ai_recommendation");

// Fetch all students
$students = mysqli_query($conn, "SELECT * FROM student");

// For each student
while($s = mysqli_fetch_assoc($students)){
    $student_id = $s['user_id'];

    // Get student skills
    $skills = [];
    $res_sk = mysqli_query($conn, "SELECT skill_id FROM student_skill WHERE student_id=$student_id");
    while($sk = mysqli_fetch_assoc($res_sk)){
        $skills[] = $sk['skill_id'];
    }

    // Fetch all postings
    $postings = mysqli_query($conn, "SELECT * FROM internship_posting");
    while($p = mysqli_fetch_assoc($postings)){
        $posting_id = $p['posting_id'];

        // Calculate skill match
        $posting_skills = [];
        $res_ps = mysqli_query($conn, "SELECT skill_id FROM posting_skill WHERE posting_id=$posting_id");
        while($ps = mysqli_fetch_assoc($res_ps)){
            $posting_skills[] = $ps['skill_id'];
        }

        $matched_skills = count(array_intersect($skills, $posting_skills));
        $total_skills = count($posting_skills) ?: 1;

        // GPA match
        $gpa_score = ($s['gpa'] >= $p['min_gpa']) ? 50 : 0;

        // Skill score out of 50
        $skill_score = intval(($matched_skills / $total_skills) * 50);

        $total_score = $gpa_score + $skill_score;
        $explanation = "GPA score: $gpa_score, Skill match: $skill_score";

        // Insert into ai_recommendation
        mysqli_query($conn, "
            INSERT INTO ai_recommendation(student_id, posting_id, score, explanation)
            VALUES($student_id, $posting_id, $total_score, '$explanation')
        ");
    }
}

echo "✅ AI recommendations generated!";
?>
