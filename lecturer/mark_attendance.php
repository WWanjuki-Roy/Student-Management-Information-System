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
$message = "";

if(isset($_POST['mark'])){
    $student = isset($_POST['student_id']) ? (int)$_POST['student_id'] : 0;
    $unit = isset($_POST['unit_id']) ? (int)$_POST['unit_id'] : 0;
    $date = date("Y-m-d");
    $status = isset($_POST['status']) ? $_POST['status'] : "";

    if ($student <= 0 || $unit <= 0 || !in_array($status, ["Present", "Absent"], true)) {
        $message = "<div class='alert alert-danger'>Please submit valid attendance details.</div>";
    } else {
        $unit_check_stmt = $conn->prepare("SELECT id FROM units WHERE id = ? AND lecturer_id = ?");
        $unit_check_stmt->bind_param("ii", $unit, $lecturer_id);
        $unit_check_stmt->execute();
        $owned_unit = $unit_check_stmt->get_result();

        if ($owned_unit->num_rows === 0) {
            $message = "<div class='alert alert-danger'>You are not allowed to mark attendance for this unit.</div>";
        } else {
            $registration_stmt = $conn->prepare("SELECT id FROM unit_registrations WHERE student_id = ? AND unit_id = ?");
            $registration_stmt->bind_param("ii", $student, $unit);
            $registration_stmt->execute();
            $is_registered = $registration_stmt->get_result();

            if ($is_registered->num_rows === 0) {
                $message = "<div class='alert alert-danger'>Student is not registered for this unit.</div>";
            } else {
                $duplicate_stmt = $conn->prepare("SELECT id FROM attendance WHERE student_id = ? AND unit_id = ? AND date = ?");
                $duplicate_stmt->bind_param("iis", $student, $unit, $date);
                $duplicate_stmt->execute();
                $existing_row = $duplicate_stmt->get_result();

                if ($existing_row->num_rows > 0) {
                    $message = "<div class='alert alert-warning'>Attendance already marked for this student today.</div>";
                } else {
                    $stmt = $conn->prepare("INSERT INTO attendance(student_id,unit_id,date,status) VALUES(?,?,?,?)");
                    $stmt->bind_param("iiss",$student,$unit,$date,$status);

                    if ($stmt->execute()) {
                        $message = "<div class='alert alert-success'>Attendance recorded.</div>";
                    } else {
                        $message = "<div class='alert alert-danger'>Failed to record attendance.</div>";
                    }

                    $stmt->close();
                }

                $duplicate_stmt->close();
            }

            $registration_stmt->close();
        }

        $unit_check_stmt->close();
    }
}
?>

<h3>Mark Attendance</h3>

<div class="card gray-card p-3">
<?= $message ?>

<form method="POST">

<div class="mb-3">
<label>Select Student</label>
<select name="student_id" class="form-control" required>

<?php
$students = $conn->query("SELECT id, fullname FROM users WHERE role='student'");
while($s = $students->fetch_assoc()){
?>
<option value="<?= (int)$s['id'] ?>"><?= htmlspecialchars($s['fullname']) ?></option>
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
<option value="<?= (int)$u['id'] ?>"><?= htmlspecialchars($u['unit_name']) ?></option>
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
