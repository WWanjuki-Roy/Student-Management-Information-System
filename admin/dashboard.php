<?php
include("../includes/auth_check.php");
include("../config/db.php");
include("../includes/header.php");
include("../includes/sidebar.php");

$students = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='student'")->fetch_assoc()['total'];
$lecturers = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='lecturer'")->fetch_assoc()['total'];
$units = $conn->query("SELECT COUNT(*) as total FROM units")->fetch_assoc()['total'];
?>

<h3>Admin Dashboard</h3>

<div class="row">
    <div class="col-md-4">
        <div class="card bg-primary text-white p-3">
            <h5>Total Students</h5>
            <h2><?= $students ?></h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white p-3">
            <h5>Total Lecturers</h5>
            <h2><?= $lecturers ?></h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white p-3">
            <h5>Total Units</h5>
            <h2><?= $units ?></h2>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
