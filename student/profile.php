<?php
include("../includes/auth_check.php");
include("../config/db.php");
include("../includes/header.php");
include("../includes/sidebar.php");

$id = $_SESSION['user_id'];

if(isset($_POST['update'])){
    $fullname = $_POST['fullname'];
    $stmt = $conn->prepare("UPDATE users SET fullname=? WHERE id=?");
    $stmt->bind_param("si",$fullname,$id);
    $stmt->execute();
    echo "<div class='alert alert-success'>Profile Updated</div>";
}

$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
?>

<h3>My Profile</h3>

<form method="POST">
<input type="text" name="fullname" class="form-control mb-3"
value="<?= $user['fullname'] ?>" required>
<input type="email" class="form-control mb-3"
value="<?= $user['email'] ?>" disabled>
<button name="update" class="btn btn-primary">Update</button>
</form>

<?php include("../includes/footer.php"); ?>
