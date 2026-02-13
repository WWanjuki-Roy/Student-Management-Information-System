<?php
include("../includes/auth_check.php");
include("../config/db.php");
include("../includes/header.php");
include("../includes/sidebar.php");

$student_id = $_SESSION['user_id'];

if(isset($_POST['register'])){
    $unit_id = $_POST['unit_id'];
    $stmt = $conn->prepare("INSERT INTO unit_registrations(student_id,unit_id) VALUES(?,?)");
    $stmt->bind_param("ii",$student_id,$unit_id);
    $stmt->execute();
}

$units = $conn->query("SELECT * FROM units");
?>

<h3>Register Units</h3>

<form method="POST">
<select name="unit_id" class="form-select mb-3" required>
<option value="">Select Unit</option>
<?php while($u = $units->fetch_assoc()): ?>
<option value="<?= $u['id'] ?>"><?= $u['unit_name'] ?></option>
<?php endwhile; ?>
</select>

<button name="register" class="btn btn-success">Register</button>
</form>

<?php include("../includes/footer.php"); ?>
