<?php
session_start();
include("../config/db.php");

$message = "";

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = $_POST['password'];

    $result = mysqli_query($conn,"SELECT * FROM user WHERE email='$email'");

    if($result && mysqli_num_rows($result) > 0){
        $user = mysqli_fetch_assoc($result);

        if(password_verify($pass, $user['password_hash'])){
            $_SESSION['uid'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            header("Location: ../".$user['role']."/dashboard.php");
            exit;
        } else {
            $message = "Incorrect Password!";
        }
    } else {
        $message = "Email not found!";
    }
}
?>

<h2>Login</h2>
<?php if($message != "") echo "<p>$message</p>"; ?>
<form method="POST">
Email: <input type="email" name="email" required><br>
Password: <input type="password" name="password" required><br>
<button name="login">Login</button>
</form>
<a href="register.php">Don't have an account? Register</a>
