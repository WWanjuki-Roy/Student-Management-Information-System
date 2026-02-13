<?php
include("../config/db.php");

if(isset($_POST['register'])){
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = "admin";

    $stmt = $conn->prepare("INSERT INTO users(fullname,email,password,role) VALUES(?,?,?,?)");
    $stmt->bind_param("ssss",$fullname,$email,$password,$role);
    $stmt->execute();

    echo "Admin Created Successfully!";
}
?>

<form method="POST">
<input type="text" name="fullname" placeholder="Full Name" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button name="register">Create Admin</button>
</form>
