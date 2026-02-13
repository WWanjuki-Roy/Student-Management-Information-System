<?php
include("../includes/auth_check.php");
include("../config/db.php");
include("../includes/header.php");
include("../includes/sidebar.php");

$student_id = $_SESSION['user_id'];

$result = $conn->query("
SELECT units.unit_name, attendance.date, attendance.status
FROM attendance
JOIN units ON attendance.unit_id = units.id
WHERE attendance.student_id = $student_id
ORDER BY attendance.date DESC
");
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
<td><?= $row['unit_name'] ?></td>
<td><?= $row['date'] ?></td>
<td><?= $row['status'] ?></td>
</tr>
<?php endwhile; ?>
</table>

<?php include("../includes/footer.php"); ?>
