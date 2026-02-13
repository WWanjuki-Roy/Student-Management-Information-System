<?php
session_start();
include("../config/db.php");

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1){
        $user = $result->fetch_assoc();

        if(password_verify($password,$user['password'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            header("Location: ../".$_SESSION['role']."/dashboard.php");
        } else {
            $error = "Invalid Password";
        }
    } else {
        $error = "User Not Found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login - SIMS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card p-4 shadow" style="width:400px;">
<h4 class="text-center">SIMS Login</h4>

<?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

<form method="POST">
<input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
<button class="btn btn-dark w-100" name="login">Login</button>
</form>
</div>

</body>
</html>
