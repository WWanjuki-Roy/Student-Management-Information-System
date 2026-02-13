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
   ADD ACADEMIC YEAR
================================ */
if(isset($_POST['add_year'])){
    $year_name = trim($_POST['year_name']);

    if(!empty($year_name)){
        $stmt = $conn->prepare("INSERT INTO academic_years(year_name) VALUES(?)");
        $stmt->bind_param("s", $year_name);
        $stmt->execute();
        $success = "Academic Year added successfully!";
    }
}

/* ==============================
   DELETE ACADEMIC YEAR
================================ */
if(isset($_GET['delete_year'])){
    $id = intval($_GET['delete_year']);
    $conn->query("DELETE FROM academic_years WHERE id = $id");
    header("Location: manage_academic.php");
    exit();
}

/* ==============================
   ADD SEMESTER
================================ */
if(isset($_POST['add_semester'])){
    $semester_name = trim($_POST['semester_name']);
    $academic_year_id = intval($_POST['academic_year_id']);

    if(!empty($semester_name)){
        $stmt = $conn->prepare("INSERT INTO semesters(semester_name, academic_year_id) VALUES(?,?)");
        $stmt->bind_param("si", $semester_name, $academic_year_id);
        $stmt->execute();
        $success = "Semester added successfully!";
    }
}

/* ==============================
   DELETE SEMESTER
================================ */
if(isset($_GET['delete_semester'])){
    $id = intval($_GET['delete_semester']);
    $conn->query("DELETE FROM semesters WHERE id = $id");
    header("Location: manage_academic.php");
    exit();
}

/* ==============================
   FETCH DATA
================================ */
$years = $conn->query("SELECT * FROM academic_years ORDER BY id DESC");

$semesters = $conn->query("
    SELECT semesters.*, academic_years.year_name 
    FROM semesters 
    JOIN academic_years ON semesters.academic_year_id = academic_years.id
    ORDER BY semesters.id DESC
");
?>

<h3>Academic Management</h3>

<?php if(isset($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<div class="row">

    <!-- ================= ADD ACADEMIC YEAR ================= -->
    <div class="col-md-6">
        <div class="card shadow-sm p-3 mb-4">
            <h5>Add Academic Year</h5>
            <form method="POST">
                <input type="text" name="year_name" class="form-control mb-3" 
                       placeholder="e.g 2025/2026" required>
                <button class="btn btn-primary w-100" name="add_year">
                    Add Academic Year
                </button>
            </form>
        </div>

        <div class="card shadow-sm p-3">
            <h5>All Academic Years</h5>
            <table class="table table-bordered table-sm">
                <tr>
                    <th>#</th>
                    <th>Year</th>
                    <th>Action</th>
                </tr>

                <?php while($row = $years->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['year_name'] ?></td>
                    <td>
                        <a href="?delete_year=<?= $row['id'] ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this academic year?')">
                           Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>

            </table>
        </div>
    </div>

    <!-- ================= ADD SEMESTER ================= -->
    <div class="col-md-6">
        <div class="card shadow-sm p-3 mb-4">
            <h5>Add Semester</h5>
            <form method="POST">
                <input type="text" name="semester_name" 
                       class="form-control mb-3"
                       placeholder="e.g Semester 1" required>

                <select name="academic_year_id" 
                        class="form-select mb-3" required>
                    <option value="">Select Academic Year</option>
                    <?php
                    $year_list = $conn->query("SELECT * FROM academic_years");
                    while($y = $year_list->fetch_assoc()):
                    ?>
                        <option value="<?= $y['id'] ?>">
                            <?= $y['year_name'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <button class="btn btn-success w-100" name="add_semester">
                    Add Semester
                </button>
            </form>
        </div>

        <div class="card shadow-sm p-3">
            <h5>All Semesters</h5>
            <table class="table table-bordered table-sm">
                <tr>
                    <th>#</th>
                    <th>Semester</th>
                    <th>Academic Year</th>
                    <th>Action</th>
                </tr>

                <?php while($row = $semesters->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['semester_name'] ?></td>
                    <td><?= $row['year_name'] ?></td>
                    <td>
                        <a href="?delete_semester=<?= $row['id'] ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this semester?')">
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
