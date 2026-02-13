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

$totalUnits = $conn->query("SELECT COUNT(*) as t FROM units WHERE lecturer_id=$lecturer_id")->fetch_assoc()['t'];
?>

<h3>Lecturer Dashboard</h3>

<div class="card bg-primary text-white p-3">
<h5>My Assigned Units</h5>
<h2><?= $totalUnits ?></h2>
</div>

<?php include("../includes/footer.php"); ?>
