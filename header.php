<?php
session_start();
if (!isset($_SESSION['userId']) || !isset($_SESSION['role'])) {
    header('Location: login.php'); // Redirect to login if not authenticated
    exit();
}

include 'config.php';

$departmentName = '';
$facultyName = '';
$facultyId = null;

// Get department name from the department ID
if (isset($_SESSION['departmentId'])) { // Check if department ID is set in the session
    $departmentId = $_SESSION['departmentId'];
    $departmentSql = "SELECT DepartmentName, FacultyID FROM Department WHERE DepartmentID = ?";
    $departmentStmt = $conn->prepare($departmentSql);
    $departmentStmt->bind_param("i", $departmentId);
    $departmentStmt->execute();
    $departmentResult = $departmentStmt->get_result();
    $department = $departmentResult->fetch_assoc();
    $departmentName = $department["DepartmentName"];
    $facultyId = $department["FacultyID"];
    $departmentStmt->close();
}

// Get faculty name if faculty ID is available
if ($facultyId) {
    $facultySql = "SELECT FacultyName FROM Faculty WHERE FacultyID = ?";
    $facultyStmt = $conn->prepare($facultySql);
    $facultyStmt->bind_param("i", $facultyId);
    $facultyStmt->execute();
    $facultyResult = $facultyStmt->get_result();
    $faculty = $facultyResult->fetch_assoc();
    $facultyName = $faculty["FacultyName"];
    $facultyStmt->close();
}

// If the user is a Dean, fetch the faculty information
if ($_SESSION['role'] == 'Dean') {
    $facultySql = "SELECT FacultyID, FacultyName AS FacultyName FROM Faculty WHERE DeanID = ?";
    $facultyStmt = $conn->prepare($facultySql);
    $facultyStmt->bind_param("i", $_SESSION['userId']);
    $facultyStmt->execute();
    $facultyResult = $facultyStmt->get_result();
    if ($faculty = $facultyResult->fetch_assoc()) {
        $facultyId = $faculty["FacultyID"];
        $facultyName = $faculty["FacultyName"];
        $_SESSION['facultyId'] = $facultyId;
        $_SESSION['facultyName'] = $facultyName;
    }
    $facultyStmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
    <p>Your Role: <strong><?php echo htmlspecialchars($_SESSION['role']); ?></strong></p>
    <?php if ($_SESSION['role'] != 'Dean' && !empty($departmentName)): ?>
        <p>Your Department: <strong><?php echo htmlspecialchars($departmentName); ?></strong></p>
    <?php endif; ?>
    <?php if (!empty($facultyName)): ?>
        <p>Your Faculty: <strong><?php echo htmlspecialchars($facultyName); ?></strong></p>
    <?php endif; ?>
    <!-- <p>Session Values: <strong><?php print_r($_SESSION); ?></strong></p> -->
    <a href="logout.php">Logout</a>
    <hr>
    <!-- Navigation links -->
</body>
</html>
