<?php
include("../includes/auth_check.php");
include("../config/db.php");
include("../includes/header.php");
include("../includes/sidebar.php");

$student_id = $_SESSION['user_id'];

$sql = "SELECT * FROM results WHERE student_id = $student_id AND published=1";
$result = $conn->query($sql);

$total_points = 0;
$count = 0;
?>

<h3>My Results</h3>

<table class="table table-striped">
<tr>
    <th>Unit</th>
    <th>Marks</th>
    <th>Grade</th>
</tr>

<?php while($row = $result->fetch_assoc()): 
    $total_points += $row['marks'];
    $count++;
?>
<tr>
    <td><?= $row['unit_id'] ?></td>
    <td><?= $row['marks'] ?></td>
    <td><?= $row['grade'] ?></td>
</tr>
<?php endwhile; ?>
</table>

<?php
if($count > 0){
    $gpa = $total_points / $count;
    echo "<h5>Average Score: ".number_format($gpa,2)."</h5>";
}
?>

<?php include("../includes/footer.php"); ?>
