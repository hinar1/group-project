<?php
include("../config/db.php");

// Fetch all students
$students = mysqli_query($conn, "SELECT * FROM student");

while($student = mysqli_fetch_assoc($students)){
    $student_id = $student['user_id'];

    // Get student skills
    $student_skills = [];
    $skill_res = mysqli_query($conn, "SELECT skill_id, proficiency_level FROM student_skill WHERE student_id=$student_id");
    while($s = mysqli_fetch_assoc($skill_res)){
        $student_skills[$s['skill_id']] = $s['proficiency_level'];
    }

    // Fetch all internship postings
    $postings = mysqli_query($conn, "SELECT * FROM internship_posting");
    while($posting = mysqli_fetch_assoc($postings)){
        $posting_id = $posting['posting_id'];

        // Get required skills
        $posting_skills = [];
        $ps = mysqli_query($conn, "SELECT skill_id, required_level FROM posting_skill WHERE posting_id=$posting_id");
        while($p = mysqli_fetch_assoc($ps)){
            $posting_skills[$p['skill_id']] = $p['required_level'];
        }

        // Calculate matching score (simple example)
        $score = 0;
        foreach($posting_skills as $sk_id => $level){
            if(isset($student_skills[$sk_id])){
                $score += min($level, $student_skills[$sk_id]) * 20; // skill contribution
            }
        }

        // GPA contribution
        if($student['gpa'] >= $posting['min_gpa']){
            $score += 20; // bonus for GPA match
        }

        $score = min($score, 100); // max 100

        $explanation = "Skills matched: ".count(array_intersect_key($student_skills, $posting_skills)).", GPA bonus applied.";

        // Insert or update AI recommendation
        $check = mysqli_query($conn, "SELECT * FROM ai_recommendation WHERE student_id=$student_id AND posting_id=$posting_id");
        if(mysqli_num_rows($check) > 0){
            mysqli_query($conn, "UPDATE ai_recommendation SET score=$score, explanation='$explanation' WHERE student_id=$student_id AND posting_id=$posting_id");
        } else {
            mysqli_query($conn, "INSERT INTO ai_recommendation(student_id, posting_id, score, explanation) VALUES($student_id,$posting_id,$score,'$explanation')");
        }
    }
}
?>
