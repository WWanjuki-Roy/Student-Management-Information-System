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

if(isset($_POST['upload'])){
    $student_id = $_POST['student_id'];
    $unit_id = $_POST['unit_id'];
    $marks = $_POST['marks'];

    $grade = ($marks >= 70) ? "A" :
             ($marks >= 60) ? "B" :
             ($marks >= 50) ? "C" :
             ($marks >= 40) ? "D" : "F";

    $stmt = $conn->prepare("INSERT INTO results(student_id,unit_id,marks,grade,published) VALUES(?,?,?,?,0)");
    $stmt->bind_param("iiis",$student_id,$unit_id,$marks,$grade);
    $stmt->execute();

    echo "<div class='alert alert-success'>Result Uploaded Successfully</div>";
}

$units = $conn->query("SELECT * FROM units WHERE lecturer_id=$lecturer_id");
?>

<h3>Upload Results</h3>

<div class="card gray-card p-3">

<form method="POST">

<div class="mb-3">
<label>Select Unit</label>
<select name="unit_id" class="form-control" required>
<option value="">Select Unit</option>
<?php while($u = $units->fetch_assoc()): ?>
<option value="<?= $u['id'] ?>"><?= $u['unit_name'] ?></option>
<?php endwhile; ?>
</select>
</div>

<div class="mb-3">
<label>Student ID</label>
<input type="number" name="student_id" class="form-control" placeholder="Enter Student ID" required>
</div>

<div class="mb-3">
<label>Marks</label>
<input type="number" name="marks" class="form-control" placeholder="Enter Marks" required>
</div>

<button name="upload" class="btn btn-primary">
Upload Result
</button>

</form>

</div>

<?php include("../includes/footer.php"); ?>