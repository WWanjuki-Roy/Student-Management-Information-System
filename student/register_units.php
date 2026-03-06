<?php
include("../includes/auth_check.php");
include("../config/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'student'){
    header("Location: ../auth/login.php");
    exit();
}

include("../includes/header.php");
include("../includes/sidebar.php");

$student_id = $_SESSION['user_id'];
$message = "";

if(isset($_POST['register'])){
    $unit_id = isset($_POST['unit_id']) ? (int)$_POST['unit_id'] : 0;

    if ($unit_id <= 0) {
        $message = "<div class='alert alert-danger'>Please select a valid unit.</div>";
    } else {
        $unit_check_stmt = $conn->prepare("SELECT id FROM units WHERE id = ?");
        $unit_check_stmt->bind_param("i", $unit_id);
        $unit_check_stmt->execute();
        $unit_exists = $unit_check_stmt->get_result();

        if ($unit_exists->num_rows === 0) {
            $message = "<div class='alert alert-danger'>Selected unit does not exist.</div>";
        } else {
            $check_stmt = $conn->prepare("SELECT id FROM unit_registrations WHERE student_id = ? AND unit_id = ?");
            $check_stmt->bind_param("ii", $student_id, $unit_id);
            $check_stmt->execute();
            $already_registered = $check_stmt->get_result();

            if ($already_registered->num_rows > 0) {
                $message = "<div class='alert alert-warning'>You are already registered for this unit.</div>";
            } else {
                $stmt = $conn->prepare("INSERT INTO unit_registrations(student_id,unit_id) VALUES(?,?)");
                $stmt->bind_param("ii",$student_id,$unit_id);

                if ($stmt->execute()) {
                    $message = "<div class='alert alert-success'>Unit registered successfully.</div>";
                } else {
                    $message = "<div class='alert alert-danger'>Failed to register unit.</div>";
                }

                $stmt->close();
            }

            $check_stmt->close();
        }

        $unit_check_stmt->close();
    }
}

$units_stmt = $conn->prepare("
SELECT u.id, u.unit_name
FROM units u
LEFT JOIN unit_registrations ur
  ON ur.unit_id = u.id AND ur.student_id = ?
WHERE ur.id IS NULL
ORDER BY u.unit_name ASC
");
$units_stmt->bind_param("i", $student_id);
$units_stmt->execute();
$units = $units_stmt->get_result();
?>

<h3>Register Units</h3>

<?= $message ?>

<form method="POST">
<select name="unit_id" class="form-select mb-3" required>
<option value="">Select Unit</option>
<?php while($u = $units->fetch_assoc()): ?>
<option value="<?= (int)$u['id'] ?>"><?= htmlspecialchars($u['unit_name']) ?></option>
<?php endwhile; ?>
</select>

<button name="register" class="btn btn-success">Register</button>
</form>

<?php $units_stmt->close(); ?>
<?php include("../includes/footer.php"); ?>
