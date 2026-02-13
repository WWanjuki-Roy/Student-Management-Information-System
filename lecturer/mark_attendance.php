<?php
include("../config/db.php");

if(isset($_POST['mark'])){
    $student = $_POST['student_id'];
    $unit = $_POST['unit_id'];
    $date = date("Y-m-d");
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO attendance(student_id,unit_id,date,status) VALUES(?,?,?,?)");
    $stmt->bind_param("iiss",$student,$unit,$date,$status);
    $stmt->execute();
}
?>
