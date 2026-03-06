<?php
include("../includes/auth_check.php");
include("../config/db.php");

if($_SESSION['role'] != 'lecturer'){
    header("Location: ../auth/login.php");
    exit();
}

include("../includes/header.php");
include("../includes/sidebar.php");

$lecturer_id = $_SESSION['user_id'];

if(isset($_POST['mark'])){
    $student = $_POST['student_id'];
    $unit = $_POST['unit_id'];
    $date = date("Y-m-d");
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO attendance(student_id,unit_id,date,status) VALUES(?,?,?,?)");
    $stmt->bind_param("iiss",$student,$unit,$date,$status);
    $stmt->execute();

    echo "<div class='alert alert-success'>Attendance Recorded</div>";
}
?>

<h3>Mark Attendance</h3>

<div class="card gray-card p-3">

<form method="POST">

<div class="mb-3">
<label>Select Student</label>
<select name="student_id" class="form-control" required>

<?php
$students = $conn->query("SELECT id, fullname FROM users WHERE role='student'");
while($s = $students->fetch_assoc()){
?>
<option value="<?= $s['id'] ?>"><?= $s['fullname'] ?></option>
<?php } ?>

</select>
</div>

<div class="mb-3">
<label>Select Unit</label>
<select name="unit_id" class="form-control" required>

<?php
$units = $conn->query("SELECT id, unit_name FROM units WHERE lecturer_id=$lecturer_id");
while($u = $units->fetch_assoc()){
?>
<option value="<?= $u['id'] ?>"><?= $u['unit_name'] ?></option>
<?php } ?>

</select>
</div>

<div class="mb-3">
<label>Status</label>
<select name="status" class="form-control">
<option value="Present">Present</option>
<option value="Absent">Absent</option>
</select>
</div>

<button type="submit" name="mark" class="btn btn-primary">
Mark Attendance
</button>

</form>

</div>

<?php include("../includes/footer.php"); ?>