<?php
include("db.php");

// Set password for all sample users
$users = [
    'student1@test.com' => 'password123',
    'student2@test.com' => 'password123',
    'employer1@test.com' => 'password123',
    'officer1@test.com' => 'password123'
];

foreach($users as $email => $pass){
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    mysqli_query($conn, "UPDATE user SET password_hash='$hash' WHERE email='$email'");
}

echo "Passwords fixed successfully!";
?>
