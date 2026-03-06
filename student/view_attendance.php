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
SELECT units.unit_name, attendance.date, attendance.status
FROM attendance
JOIN units ON attendance.unit_id = units.id
WHERE attendance.student_id = ?
ORDER BY attendance.date DESC
");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h3>My Attendance</h3>

<table class="table table-bordered">
<tr>
<th>Unit</th>
<th>Date</th>
<th>Status</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($row['unit_name']) ?></td>
<td><?= htmlspecialchars($row['date']) ?></td>
<td><?= htmlspecialchars($row['status']) ?></td>
</tr>
<?php endwhile; ?>
</table>

<?php $stmt->close(); ?>
<?php include("../includes/footer.php"); ?>
