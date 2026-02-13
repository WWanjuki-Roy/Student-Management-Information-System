<?php
include("../includes/auth_check.php");
include("../config/db.php");

if($_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

include("../includes/header.php");
include("../includes/sidebar.php");

/* ==============================
   ADD UNIT
================================ */
if(isset($_POST['add_unit'])){
    $unit_code = trim($_POST['unit_code']);
    $unit_name = trim($_POST['unit_name']);
    $lecturer_id = intval($_POST['lecturer_id']);
    $semester_id = intval($_POST['semester_id']);

    if(!empty($unit_code) && !empty($unit_name)){
        $stmt = $conn->prepare("INSERT INTO units(unit_code, unit_name, lecturer_id, semester_id) VALUES(?,?,?,?)");
        $stmt->bind_param("ssii", $unit_code, $unit_name, $lecturer_id, $semester_id);
        $stmt->execute();
        $success = "Unit added successfully!";
    }
}

/* ==============================
   DELETE UNIT
================================ */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM units WHERE id = $id");
    header("Location: manage_units.php");
    exit();
}

/* ==============================
   FETCH LECTURERS & SEMESTERS
================================ */
$lecturers = $conn->query("SELECT id, fullname FROM users WHERE role='lecturer'");
$semesters = $conn->query("
    SELECT semesters.*, academic_years.year_name 
    FROM semesters
    JOIN academic_years ON semesters.academic_year_id = academic_years.id
");

/* ==============================
   FETCH UNITS
================================ */
$units = $conn->query("
    SELECT units.*, users.fullname AS lecturer_name,
           semesters.semester_name, academic_years.year_name
    FROM units
    LEFT JOIN users ON units.lecturer_id = users.id
    LEFT JOIN semesters ON units.semester_id = semesters.id
    LEFT JOIN academic_years ON semesters.academic_year_id = academic_years.id
    ORDER BY units.id DESC
");
?>

<h3>Manage Units</h3>

<?php if(isset($success)): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<div class="row">

    <!-- ================= ADD UNIT FORM ================= -->
    <div class="col-md-4">
        <div class="card shadow-sm p-3 mb-4">
            <h5>Add New Unit</h5>
            <form method="POST">

                <input type="text" name="unit_code" 
                       class="form-control mb-3"
                       placeholder="Unit Code (e.g CSC101)" required>

                <input type="text" name="unit_name" 
                       class="form-control mb-3"
                       placeholder="Unit Name" required>

                <select name="lecturer_id" class="form-select mb-3" required>
                    <option value="">Assign Lecturer</option>
                    <?php while($lec = $lecturers->fetch_assoc()): ?>
                        <option value="<?= $lec['id'] ?>">
                            <?= $lec['fullname'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <select name="semester_id" class="form-select mb-3" required>
                    <option value="">Select Semester</option>
                    <?php while($sem = $semesters->fetch_assoc()): ?>
                        <option value="<?= $sem['id'] ?>">
                            <?= $sem['semester_name'] ?> 
                            (<?= $sem['year_name'] ?>)
                        </option>
                    <?php endwhile; ?>
                </select>

                <button class="btn btn-primary w-100" name="add_unit">
                    Add Unit
                </button>

            </form>
        </div>
    </div>

    <!-- ================= UNIT LIST ================= -->
    <div class="col-md-8">
        <div class="card shadow-sm p-3">
            <h5>All Units</h5>

            <table class="table table-bordered table-striped">
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Lecturer</th>
                    <th>Semester</th>
                    <th>Year</th>
                    <th>Action</th>
                </tr>

                <?php while($row = $units->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['unit_code'] ?></td>
                    <td><?= $row['unit_name'] ?></td>
                    <td><?= $row['lecturer_name'] ?? 'Not Assigned' ?></td>
                    <td><?= $row['semester_name'] ?? '-' ?></td>
                    <td><?= $row['year_name'] ?? '-' ?></td>
                    <td>
                        <a href="?delete=<?= $row['id'] ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this unit?')">
                           Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>

            </table>
        </div>
    </div>

</div>

<?php include("../includes/footer.php"); ?>
