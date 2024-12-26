<?php
include 'config.php';

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['userId']) || $_SESSION['role'] != 'Dean') {
    echo "Access Denied.";
    exit();
}

// Get FacultyID from session or fetch from the database if not set
if (!isset($_SESSION['facultyId'])) {
    $userId = $_SESSION['userId'];
    $facultyQuery = "SELECT FacultyID, FacultyName FROM Faculty WHERE DeanID = ?";
    $stmt = $conn->prepare($facultyQuery);
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($faculty = $result->fetch_assoc()) {
            $_SESSION['facultyId'] = $faculty['FacultyID'];
            $_SESSION['facultyName'] = $faculty['FacultyName'];
        }
        $stmt->close();
    } else {
        echo "Error preparing faculty query: " . $conn->error;
        exit();
    }
}

$facultyId = $_SESSION['facultyId']; // Assume faculty ID is now stored in session

// Fetch departments for dropdown
$deptQuery = "SELECT DepartmentID, DepartmentName FROM Department WHERE FacultyID = ?";
$deptStmt = $conn->prepare($deptQuery);
if ($deptStmt) {
    $deptStmt->bind_param("i", $facultyId);
    $deptStmt->execute();
    $deptResult = $deptStmt->get_result();
} else {
    echo "Error preparing department query: " . $conn->error;
    exit();
}

// Display exams filtered by department
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['departmentId'])) {
    $deptId = $_POST['departmentId'];
    $examQuery = $conn->prepare("SELECT e.ExamDate, e.StartTime, e.EndTime, c.Name FROM Exam e JOIN Course c ON e.CourseID = c.CourseID WHERE c.DepartmentID = ? ORDER BY e.ExamDate, e.StartTime");
    if ($examQuery) {
        $examQuery->bind_param("i", $deptId);
        $examQuery->execute();
        $examResult = $examQuery->get_result();

        echo "<h2>Exam Schedule</h2>";
        echo "<table border='1'><tr><th>Course Name</th><th>Exam Date</th><th>Start Time</th><th>End Time</th></tr>";
        while ($row = $examResult->fetch_assoc()) {
            echo "<tr><td>" . htmlspecialchars($row['Name']) . "</td><td>" . htmlspecialchars($row['ExamDate']) . "</td><td>" . htmlspecialchars($row['StartTime']) . "</td><td>" . htmlspecialchars($row['EndTime']) . "</td></tr>";
        }
        echo "</table>";
        $examQuery->close();
    } else {
        echo "Error preparing exam query: " . $conn->error;
    }
}

$deptStmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dean Dashboard</title>
</head>
<body>
    <h2>Select a Department to View Exams</h2>
    <form method="post" action="">
        <label for="departmentId">Department:</label>
        <select name="departmentId" id="departmentId">
            <?php while ($deptRow = $deptResult->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($deptRow['DepartmentID']); ?>"><?= htmlspecialchars($deptRow['DepartmentName']); ?></option>
            <?php endwhile; ?>
        </select>
        <input type="submit" value="Show Exams">
    </form>
</body>
</html>
