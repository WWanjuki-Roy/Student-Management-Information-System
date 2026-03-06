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

$sql = "SELECT * FROM units WHERE lecturer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$lecturer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h3>My Units</h3>

<div class="card gray-card p-3">

<table class="table table-bordered">
<tr>
    <th>Unit Name</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>

<tr>
    <td><?= $row['unit_name'] ?></td>
</tr>

<?php endwhile; ?>

</table>

</div>

<?php include("../includes/footer.php"); ?>
