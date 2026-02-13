<?php
include("../includes/auth_check.php");
include("../config/db.php");

if($_SESSION['role'] != 'student'){
    header("Location: ../auth/login.php");
    exit();
}

include("../includes/header.php");
include("../includes/sidebar.php");

$student_id = $_SESSION['user_id'];

$totalUnits = $conn->query("SELECT COUNT(*) as t FROM unit_registrations WHERE student_id=$student_id")->fetch_assoc()['t'];
?>

<h3>Student Dashboard</h3>

<div class="card bg-success text-white p-3">
<h5>Registered Units</h5>
<h2><?= $totalUnits ?></h2>
</div>

<?php include("../includes/footer.php"); ?>
