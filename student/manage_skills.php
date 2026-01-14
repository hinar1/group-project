<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'student'){
    header("Location: ../index.php");
    exit;
}

$student_id = $_SESSION['uid'];

// Add skill
if(isset($_POST['add_skill'])){
    $skill_id = $_POST['skill_id'];
    $level = $_POST['level'];

    // check if already exists
    $check = mysqli_query($conn, "SELECT * FROM student_skill WHERE student_id=$student_id AND skill_id=$skill_id");
    if(mysqli_num_rows($check) > 0){
        mysqli_query($conn, "UPDATE student_skill SET proficiency_level=$level WHERE student_id=$student_id AND skill_id=$skill_id");
        $msg = "✅ Skill updated successfully!";
    } else {
        mysqli_query($conn, "INSERT INTO student_skill(student_id, skill_id, proficiency_level) VALUES($student_id,$skill_id,$level)");
        $msg = "✅ Skill added successfully!";
    }
}

// Fetch skills
$skills = mysqli_query($conn, "SELECT * FROM skill");
$student_skills = mysqli_query($conn, "
    SELECT s.name, ss.proficiency_level 
    FROM student_skill ss 
    JOIN skill s ON ss.skill_id=s.skill_id
    WHERE ss.student_id=$student_id
");
?>

<link rel="stylesheet" href="style.css">
<div class="container">
<h2>Manage Skills</h2>

<?php if(isset($msg)) echo "<p>$msg</p>"; ?>

<form method="POST">
Skill:
<select name="skill_id" required>
<option value="">Select Skill</option>
<?php while($s = mysqli_fetch_assoc($skills)){
    echo "<option value='{$s['skill_id']}'>{$s['name']}</option>";
} ?>
</select>

Proficiency Level (1-5):
<input type="number" name="level" min="1" max="5" required>
<button name="add_skill">Add / Update Skill</button>
</form>

<h3>Your Skills</h3>
<table>
<tr><th>Skill</th><th>Level</th></tr>
<?php
if(mysqli_num_rows($student_skills) > 0){
    while($ss = mysqli_fetch_assoc($student_skills)){
        echo "<tr><td>{$ss['name']}</td><td>{$ss['proficiency_level']}</td></tr>";
    }
} else {
    echo "<tr><td colspan='2'>No skills added yet.</td></tr>";
}
?>
</table>

<a class="button" href="dashboard.php">Back to Dashboard</a>
</div>
