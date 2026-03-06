<?php
include("../includes/auth_check.php");
include("../config/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'lecturer'){
    header("Location: ../auth/login.php");
    exit();
}

include("../includes/header.php");
include("../includes/sidebar.php");

$lecturer_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
SELECT u.unit_name, 
COUNT(a.id) as total_classes,
SUM(CASE WHEN a.status='Present' THEN 1 ELSE 0 END) as present_count
FROM attendance a
JOIN units u ON a.unit_id = u.id
WHERE u.lecturer_id = ?
GROUP BY u.id
");
$stmt->bind_param("i", $lecturer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h3>Attendance Summary</h3>

<table class="table table-bordered">
<tr>
    <th>Unit</th>
    <th>Total Classes</th>
    <th>Present</th>
    <th>Percentage</th>
</tr>

<?php while($row = $result->fetch_assoc()): 
    $percentage = 0;
    if ((int)$row['total_classes'] > 0) {
        $percentage = ($row['present_count'] / $row['total_classes']) * 100;
    }
?>
<tr>
    <td><?= htmlspecialchars($row['unit_name']) ?></td>
    <td><?= (int)$row['total_classes'] ?></td>
    <td><?= (int)$row['present_count'] ?></td>
    <td><?= number_format($percentage,2) ?>%</td>
</tr>
<?php endwhile; ?>
</table>

<?php $stmt->close(); ?>
<?php include("../includes/footer.php"); ?>
