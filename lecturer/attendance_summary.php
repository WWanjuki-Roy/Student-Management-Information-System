<?php
include("../includes/auth_check.php");
include("../config/db.php");
include("../includes/header.php");
include("../includes/sidebar.php");

$lecturer_id = $_SESSION['user_id'];

$sql = "
SELECT u.unit_name, 
COUNT(a.id) as total_classes,
SUM(CASE WHEN a.status='Present' THEN 1 ELSE 0 END) as present_count
FROM attendance a
JOIN units u ON a.unit_id = u.id
WHERE u.lecturer_id = $lecturer_id
GROUP BY u.id
";

$result = $conn->query($sql);
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
    $percentage = ($row['present_count']/$row['total_classes'])*100;
?>
<tr>
    <td><?= $row['unit_name'] ?></td>
    <td><?= $row['total_classes'] ?></td>
    <td><?= $row['present_count'] ?></td>
    <td><?= number_format($percentage,2) ?>%</td>
</tr>
<?php endwhile; ?>
</table>

<?php include("../includes/footer.php"); ?>
