<?php
include("../config/db.php");

$message = "";

if(isset($_POST['register'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM user WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        $message = "Email already registered!";
    } else {
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert into user table
        mysqli_query($conn, "INSERT INTO user(email,password_hash,role) VALUES('$email','$password_hash','$role')");

        $user_id = mysqli_insert_id($conn);

        // If student, insert into student table
        if($role == 'student'){
            mysqli_query($conn, "INSERT INTO student(user_id, roll_no, program, year_of_study, gpa, preferred_domains) 
                                VALUES($user_id,'','',0,0,'')");
        }

        // If officer
        if($role == 'officer'){
            mysqli_query($conn, "INSERT INTO placement_officer(user_id, office_location, phone) 
                                VALUES($user_id,'','')");
        }

        // If employer
        if($role == 'employer'){
            mysqli_query($conn, "INSERT INTO employer(user_id, company_id, designation, phone) 
                                VALUES($user_id, NULL,'','')");
        }

        $message = "Registered Successfully! You can now login.";
    }
}
?>

<h2>Register</h2>
<?php if($message != "") echo "<p>$message</p>"; ?>
<form method="POST">
Email: <input type="email" name="email" required><br>
Password: <input type="password" name="password" required><br>
Role:
<select name="role">
<option value="student">Student</option>
<option value="employer">Employer</option>
<option value="officer">Placement Officer</option>
</select><br>
<button name="register">Register</button>
</form>
<a href="login.php">Already have an account? Login</a>
