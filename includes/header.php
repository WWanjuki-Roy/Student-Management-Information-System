<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>SIMS Portal</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand">SIMS Portal</span>
    <a href="../auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
  </div>
</nav>

<div class="container-fluid">
<div class="row">
