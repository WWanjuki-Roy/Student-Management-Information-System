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
   ADD LECTURER
================================ */
if(isset($_POST['add_lecturer'])){
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = "lecturer";

    // Check if email exists
    $check = $conn->prepare("SELECT id FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if($check->num_rows > 0){
        $error = "Email already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users(fullname,email,password,role) VALUES(?,?,?,?)");
        $stmt->bind_param("ssss", $fullname, $email, $password, $role);
        $stmt->execute();
        $success = "Lecturer added successfully!";
    }
}

/* ==============================
   DELETE LECTURER
================================ */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);

    // Check if lecturer has assigned units
    $checkUnits = $conn->query("SELECT id FROM units WHERE lecturer_id = $id");

    if($checkUnits->num_rows > 0){
        $error = "Cannot delete lecturer. They are assigned to units.";
    } else {
        $conn->query("DELETE FROM users WHERE id = $id AND role='lecturer'");
        header("Location: manage_lecturers.php");
        exit();
    }
}

/* ==============================
   EDIT LECTURER
================================ */
if(isset($_POST['update_lecturer'])){
    $id = intval($_POST['lecturer_id']);
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("UPDATE users SET fullname=?, email=? WHERE id=? AND role='lecturer'");
    $stmt->bind_param("ssi", $fullname, $email, $id);
    $stmt->execute();

    $success = "Lecturer updated successfully!";
}

/* ==============================
   FETCH LECTURERS
================================ */
$lecturers = $conn->query("SELECT * FROM users WHERE role='lecturer' ORDER BY id DESC");

/* ==============================
   FETCH FOR EDIT
================================ */
$editData = null;
if(isset($_GET['edit'])){
    $id = intval($_GET['edit']);
    $editData = $conn->query("SELECT * FROM users WHERE id=$id AND role='lecturer'")->fetch_assoc();
}
?>

<h3>Manage Lecturers</h3>

<?php if(isset($success)): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<?php if(isset($error)): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="row">

    <!-- ================= ADD / EDIT FORM ================= -->
    <div class="col-md-4">
        <div class="card shadow-sm p-3 mb-4">
            <h5><?= $editData ? "Edit Lecturer" : "Add Lecturer" ?></h5>

            <form method="POST">

                <input type="hidden" name="lecturer_id" 
                       value="<?= $editData['id'] ?? '' ?>">

                <input type="text" name="fullname"
                       class="form-control mb-3"
                       placeholder="Full Name"
                       value="<?= $editData['fullname'] ?? '' ?>" required>

                <input type="email" name="email"
                       class="form-control mb-3"
                       placeholder="Email"
                       value="<?= $editData['email'] ?? '' ?>" required>

                <?php if(!$editData): ?>
                <input type="password" name="password"
                       class="form-control mb-3"
                       placeholder="Password" required>
                <?php endif; ?>

                <?php if($editData): ?>
                    <button class="btn btn-success w-100" name="update_lecturer">
                        Update Lecturer
                    </button>
                <?php else: ?>
                    <button class="btn btn-primary w-100" name="add_lecturer">
                        Add Lecturer
                    </button>
                <?php endif; ?>

            </form>
        </div>
    </div>

    <!-- ================= LECTURER LIST ================= -->
    <div class="col-md-8">
        <div class="card shadow-sm p-3">
            <h5>All Lecturers</h5>

            <table class="table table-bordered table-striped">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>

                <?php while($row = $lecturers->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['fullname'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td>
                        <a href="?edit=<?= $row['id'] ?>" 
                           class="btn btn-warning btn-sm">Edit</a>

                        <a href="?delete=<?= $row['id'] ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this lecturer?')">
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
