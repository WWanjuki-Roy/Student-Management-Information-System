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
$stmt = $conn->prepare("
SELECT units.unit_name, units.unit_code
FROM unit_registrations
JOIN units ON unit_registrations.unit_id = units.id
WHERE unit_registrations.student_id = ?
");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h3>My Units</h3>

<table class="table table-bordered">
<tr><th>Code</th><th>Unit</th></tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($row['unit_code']) ?></td>
<td><?= htmlspecialchars($row['unit_name']) ?></td>
</tr>
<?php endwhile; ?>
</table>

<?php $stmt->close(); ?>
<?php include("../includes/footer.php"); ?>
