<?php
include("../includes/auth_check.php");
include("../config/db.php");

if($_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

include("../includes/header.php");
include("../includes/sidebar.php");

// Summary Queries
$totalStudents = $conn->query("SELECT COUNT(*) as t FROM users WHERE role='student'")->fetch_assoc()['t'];
$totalLecturers = $conn->query("SELECT COUNT(*) as t FROM users WHERE role='lecturer'")->fetch_assoc()['t'];
$totalUnits = $conn->query("SELECT COUNT(*) as t FROM units")->fetch_assoc()['t'];
$totalRegistrations = $conn->query("SELECT COUNT(*) as t FROM unit_registrations")->fetch_assoc()['t'];
?>

<h3>System Reports</h3>

<div class="row">

<div class="col-md-3">
<div class="card bg-primary text-white p-3">
<h6>Total Students</h6>
<h2><?= $totalStudents ?></h2>
</div>
</div>

<div class="col-md-3">
<div class="card bg-success text-white p-3">
<h6>Total Lecturers</h6>
<h2><?= $totalLecturers ?></h2>
</div>
</div>

<div class="col-md-3">
<div class="card bg-warning text-white p-3">
<h6>Total Units</h6>
<h2><?= $totalUnits ?></h2>
</div>
</div>

<div class="col-md-3">
<div class="card bg-danger text-white p-3">
<h6>Total Registrations</h6>
<h2><?= $totalRegistrations ?></h2>
</div>
</div>

</div>

<?php include("../includes/footer.php"); ?>
