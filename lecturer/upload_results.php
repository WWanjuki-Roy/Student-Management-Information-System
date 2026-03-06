<?php
include("../includes/auth_check.php");
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'lecturer') {
    header("Location: ../auth/login.php");
    exit();
}

include("../includes/header.php");
include("../includes/sidebar.php");

$lecturer_id = $_SESSION['user_id'];
$message = "";

// Fetch lecturer units safely
$unit_stmt = $conn->prepare("SELECT id, unit_name FROM units WHERE lecturer_id = ?");
$unit_stmt->bind_param("i", $lecturer_id);
$unit_stmt->execute();
$units = $unit_stmt->get_result();

if (isset($_POST['upload'])) {
    $student_id = isset($_POST['student_id']) ? (int)$_POST['student_id'] : 0;
    $unit_id = isset($_POST['unit_id']) ? (int)$_POST['unit_id'] : 0;
    $marks = isset($_POST['marks']) ? (int)$_POST['marks'] : -1;

    if ($student_id <= 0 || $unit_id <= 0 || $marks < 0 || $marks > 100) {
        $message = "<div class='alert alert-danger'>Please enter valid student ID, unit, and marks between 0 and 100.</div>";
    } else {
        $unit_check_stmt = $conn->prepare("SELECT id FROM units WHERE id = ? AND lecturer_id = ?");
        $unit_check_stmt->bind_param("ii", $unit_id, $lecturer_id);
        $unit_check_stmt->execute();
        $owned_unit = $unit_check_stmt->get_result();

        if ($owned_unit->num_rows === 0) {
            $message = "<div class='alert alert-danger'>You are not allowed to upload results for that unit.</div>";
        } else {
            $student_check_stmt = $conn->prepare("SELECT id FROM users WHERE id = ? AND role = 'student'");
            $student_check_stmt->bind_param("i", $student_id);
            $student_check_stmt->execute();
            $student_exists = $student_check_stmt->get_result();

            if ($student_exists->num_rows === 0) {
                $message = "<div class='alert alert-danger'>Selected student does not exist.</div>";
            } else {
                $registration_stmt = $conn->prepare("SELECT id FROM unit_registrations WHERE student_id = ? AND unit_id = ?");
                $registration_stmt->bind_param("ii", $student_id, $unit_id);
                $registration_stmt->execute();
                $is_registered = $registration_stmt->get_result();

                if ($is_registered->num_rows === 0) {
                    $message = "<div class='alert alert-danger'>Student is not registered for the selected unit.</div>";
                } else {
                    if ($marks >= 70) {
                        $grade = "A";
                    } elseif ($marks >= 60) {
                        $grade = "B";
                    } elseif ($marks >= 50) {
                        $grade = "C";
                    } elseif ($marks >= 40) {
                        $grade = "D";
                    } else {
                        $grade = "F";
                    }

                    $check_stmt = $conn->prepare("SELECT id FROM results WHERE student_id = ? AND unit_id = ?");
                    $check_stmt->bind_param("ii", $student_id, $unit_id);
                    $check_stmt->execute();
                    $existing_result = $check_stmt->get_result();

                    if ($existing_result->num_rows > 0) {
                        $message = "<div class='alert alert-warning'>Result for this student and unit already exists.</div>";
                    } else {
                        $insert_stmt = $conn->prepare("INSERT INTO results (student_id, unit_id, marks, grade, published) VALUES (?, ?, ?, ?, 0)");
                        $insert_stmt->bind_param("iiis", $student_id, $unit_id, $marks, $grade);

                        if ($insert_stmt->execute()) {
                            $message = "<div class='alert alert-success'>Result uploaded successfully.</div>";
                        } else {
                            $message = "<div class='alert alert-danger'>Failed to upload result.</div>";
                        }

                        $insert_stmt->close();
                    }

                    $check_stmt->close();
                }

                $registration_stmt->close();
            }

            $student_check_stmt->close();
        }

        $unit_check_stmt->close();
    }
}
?>

<h3>Upload Results</h3>

<div class="card gray-card p-3">
    <?php echo $message; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Select Unit</label>
            <select name="unit_id" class="form-control" required>
                <option value="">Select Unit</option>
                <?php while ($u = $units->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($u['id']) ?>">
                        <?= htmlspecialchars($u['unit_name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Student ID</label>
            <input type="number" name="student_id" class="form-control" placeholder="Enter Student ID" required>
        </div>

        <div class="mb-3">
            <label>Marks</label>
            <input type="number" name="marks" class="form-control" placeholder="Enter Marks" min="0" max="100" required>
        </div>

        <button type="submit" name="upload" class="btn btn-primary">
            Upload Result
        </button>
    </form>
</div>

<?php
$unit_stmt->close();
include("../includes/footer.php");
?>
