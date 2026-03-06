<?php
include("../includes/auth_check.php");
include("../config/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

include("../includes/header.php");
include("../includes/sidebar.php");

$message = "";

if (isset($_POST['publish_one'])) {
    $result_id = isset($_POST['result_id']) ? (int)$_POST['result_id'] : 0;
    if ($result_id > 0) {
        $stmt = $conn->prepare("UPDATE results SET published = 1 WHERE id = ?");
        $stmt->bind_param("i", $result_id);
        $stmt->execute();
        $stmt->close();
        $message = "<div class='alert alert-success'>Result published.</div>";
    }
}

if (isset($_POST['unpublish_one'])) {
    $result_id = isset($_POST['result_id']) ? (int)$_POST['result_id'] : 0;
    if ($result_id > 0) {
        $stmt = $conn->prepare("UPDATE results SET published = 0 WHERE id = ?");
        $stmt->bind_param("i", $result_id);
        $stmt->execute();
        $stmt->close();
        $message = "<div class='alert alert-warning'>Result unpublished.</div>";
    }
}

if (isset($_POST['publish_all'])) {
    $stmt = $conn->prepare("UPDATE results SET published = 1 WHERE published = 0");
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();
    $message = "<div class='alert alert-success'>Published {$affected} result(s).</div>";
}

$results = $conn->query("
SELECT 
    results.id,
    students.fullname AS student_name,
    units.unit_code,
    units.unit_name,
    results.marks,
    results.grade,
    results.published
FROM results
JOIN users AS students ON results.student_id = students.id
JOIN units ON results.unit_id = units.id
ORDER BY results.id DESC
");
?>

<h3>Publish Results</h3>

<?= $message ?>

<form method="POST" class="mb-3">
    <button type="submit" name="publish_all" class="btn btn-success">Publish All Unpublished</button>
</form>

<div class="card shadow-sm p-3">
    <table class="table table-bordered table-striped">
        <tr>
            <th>ID</th>
            <th>Student</th>
            <th>Code</th>
            <th>Unit</th>
            <th>Marks</th>
            <th>Grade</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while($row = $results->fetch_assoc()): ?>
        <tr>
            <td><?= (int)$row['id'] ?></td>
            <td><?= htmlspecialchars($row['student_name']) ?></td>
            <td><?= htmlspecialchars($row['unit_code']) ?></td>
            <td><?= htmlspecialchars($row['unit_name']) ?></td>
            <td><?= (int)$row['marks'] ?></td>
            <td><?= htmlspecialchars($row['grade']) ?></td>
            <td>
                <?php if ((int)$row['published'] === 1): ?>
                    <span class="badge text-bg-success">Published</span>
                <?php else: ?>
                    <span class="badge text-bg-secondary">Unpublished</span>
                <?php endif; ?>
            </td>
            <td>
                <form method="POST" class="d-inline">
                    <input type="hidden" name="result_id" value="<?= (int)$row['id'] ?>">
                    <?php if ((int)$row['published'] === 1): ?>
                        <button type="submit" name="unpublish_one" class="btn btn-warning btn-sm">Unpublish</button>
                    <?php else: ?>
                        <button type="submit" name="publish_one" class="btn btn-primary btn-sm">Publish</button>
                    <?php endif; ?>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include("../includes/footer.php"); ?>
