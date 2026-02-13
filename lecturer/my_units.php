<?php
include("../includes/auth_check.php");
include("../config/db.php");

$lecturer_id = $_SESSION['user_id'];

$sql = "SELECT * FROM units WHERE lecturer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$lecturer_id);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()){
    echo $row['unit_name'] . "<br>";
}
?>
