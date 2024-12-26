<?php
include 'config.php';  // Include database configuration file
include"header.php";  // Include header file

if (!isset($_SESSION['userId'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Exam Planning System</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .dashboard { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="dashboard">
        <h2>Your Dashboard</h2>
        <ul>
            <?php if ($_SESSION['role'] == 'Assistant'): ?>
                <!-- <li><a href="assistant.php">View Your Schedule</a></li> -->
                <?php include 'assistant.php'; ?>
            <?php endif; ?>
            <?php if ($_SESSION['role'] == 'Secretary'): ?>
                <!-- <li><a href="secretary.php">Manage Exams and Courses</a></li> -->
                <?php include 'secretary.php'; ?>
            <?php endif; ?>
            <?php if ($_SESSION['role'] == 'Head of Department'): ?>
                <!-- <li><a href="head_department.php">Department Overview</a></li> -->
                <?php include 'head_department.php'; ?>
            <?php endif; ?>
            <?php if ($_SESSION['role'] == 'Head of Secretary'): ?>
                <!-- <li><a href="head_secretary.php">Faculty Exam Management</a></li> -->
                <?php include 'head_secretary.php'; ?>
            <?php endif; ?>
            <?php if ($_SESSION['role'] == 'Dean'): ?>
                <!-- <li><a href="dean.php">View Faculty Exams</a></li> -->
                <?php include 'dean.php'; ?>
            <?php endif; ?>
            <?php if ($_SESSION['role'] == 'Admin'): ?>
                <!-- <li><a href="dean.php">View Faculty Exams</a></li> -->
                <?php include 'admin.php'; ?>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
