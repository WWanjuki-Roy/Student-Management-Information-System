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
SELECT units.unit_code, units.unit_name, results.marks, results.grade
FROM results
JOIN units ON results.unit_id = units.id
WHERE results.student_id = ? AND results.published = 1
ORDER BY units.unit_name ASC
");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$total_points = 0;
$count = 0;
?>

<h3>My Results</h3>

<table class="table table-striped">
<tr>
    <th>Code</th>
    <th>Unit</th>
    <th>Marks</th>
    <th>Grade</th>
</tr>

<?php while($row = $result->fetch_assoc()): 
    $total_points += $row['marks'];
    $count++;
?>
<tr>
    <td><?= htmlspecialchars($row['unit_code']) ?></td>
    <td><?= htmlspecialchars($row['unit_name']) ?></td>
    <td><?= (int)$row['marks'] ?></td>
    <td><?= htmlspecialchars($row['grade']) ?></td>
</tr>
<?php endwhile; ?>
</table>

<?php
if($count > 0){
    $gpa = $total_points / $count;
    echo "<h5>Average Score: ".number_format($gpa,2)."</h5>";
}
?>

<?php $stmt->close(); ?>
<?php include("../includes/footer.php"); ?>
