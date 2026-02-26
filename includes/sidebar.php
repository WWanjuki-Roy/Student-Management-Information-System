<div class="col-md-2 sidebar vh-100 p-3">
    <ul class="nav flex-column">

        <?php if($_SESSION['role'] == 'admin'): ?>
            <li><a class="nav-link" href="../admin/dashboard.php">Dashboard</a></li>
            <li><a class="nav-link" href="../admin/manage_students.php">Students</a></li>
            <li><a class="nav-link" href="../admin/manage_lecturers.php">Lecturers</a></li>
            <li><a class="nav-link" href="../admin/manage_units.php">Units</a></li>
            <li><a class="nav-link" href="../admin/manage_academic.php">Academic Setup</a></li>
            <li><a class="nav-link" href="../admin/view_reports.php">Reports</a></li>
        <?php endif; ?>

        <?php if($_SESSION['role'] == 'lecturer'): ?>
            <li><a class="nav-link" href="../lecturer/dashboard.php">Dashboard</a></li>
            <li><a class="nav-link" href="../lecturer/my_units.php">My Units</a></li>
            <li><a class="nav-link" href="../lecturer/mark_attendance.php">Attendance</a></li>
            <li><a class="nav-link" href="../lecturer/upload_results.php">Results</a></li>
            <li><a class="nav-link" href="../lecturer/attendance_summary.php">Summary</a></li>
        <?php endif; ?>

        <?php if($_SESSION['role'] == 'student'): ?>
            <li><a class="nav-link" href="../student/dashboard.php">Dashboard</a></li>
            <li><a class="nav-link" href="../student/register_units.php">Register Units</a></li>
            <li><a class="nav-link" href="../student/my_units.php">My Units</a></li>
            <li><a class="nav-link" href="../student/view_attendance.php">Attendance</a></li>
            <li><a class="nav-link" href="../student/view_results.php">Results</a></li>
            <li><a class="nav-link" href="../student/profile.php">Profile</a></li>
        <?php endif; ?>

    </ul>
</div>

<div class="col-md-10 p-4">
