<?php
include("../includes/auth_check.php");
include("../config/db.php");
include("../includes/header.php");
include("../includes/sidebar.php");

$student_id = $_SESSION['user_id'];

$result = $conn->query("
SELECT units.unit_name, units.unit_code
FROM unit_registrations
JOIN units ON unit_registrations.unit_id = units.id
WHERE unit_registrations.student_id = $student_id
");
?>

<h3>My Units</h3>

<table class="table table-bordered">
<tr><th>Code</th><th>Unit</th></tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $row['unit_code'] ?></td>
<td><?= $row['unit_name'] ?></td>
</tr>
<?php endwhile; ?>
</table>

<?php include("../includes/footer.php"); ?>
