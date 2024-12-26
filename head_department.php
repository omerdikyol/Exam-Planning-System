<?php
include 'config.php';

if (!isset($_SESSION['userId']) || $_SESSION['role'] != 'Head of Department') {
    header("Location: login.php");  // Redirect if no session or wrong role
    exit();
}

$deptId = $_SESSION['departmentId'];

// Display department exams
$query = "
    SELECT c.Name AS CourseName, e.ExamDate, e.StartTime, e.EndTime
    FROM Exam e
    JOIN Course c ON e.CourseID = c.CourseID
    WHERE c.DepartmentID = ?
    ORDER BY e.ExamDate ASC, e.StartTime ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $deptId);
$stmt->execute();
$result = $stmt->get_result();

$exams = [];
while ($row = $result->fetch_assoc()) {
    $exams[] = $row;
}
$stmt->close();

// Calculate workloads of department assistants
$workloadQuery = "
    SELECT e.Name AS AssistantName, e.AssistantScore, 
           (e.AssistantScore / (SELECT SUM(AssistantScore) FROM Employee WHERE Role = 'Assistant' AND DepartmentID = ?)) * 100 AS Percentage
    FROM Employee e
    WHERE e.Role = 'Assistant' AND e.DepartmentID = ?
    ORDER BY e.AssistantScore DESC";
$workloadStmt = $conn->prepare($workloadQuery);
$workloadStmt->bind_param("ii", $deptId, $deptId);
$workloadStmt->execute();
$workloadResult = $workloadStmt->get_result();

$workloads = [];
while ($row = $workloadResult->fetch_assoc()) {
    $workloads[] = $row;
}
$workloadStmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Head of Department Dashboard</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Department Exam Schedule</h2>
    <table>
        <tr>
            <th>Course Name</th>
            <th>Exam Date</th>
            <th>Exam Time</th>
        </tr>
        <?php foreach ($exams as $exam): ?>
            <tr>
                <td><?= htmlspecialchars($exam['CourseName']); ?></td>
                <td><?= htmlspecialchars($exam['ExamDate']); ?></td>
                <td><?= htmlspecialchars($exam['StartTime']) . ' - ' . htmlspecialchars($exam['EndTime']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Assistant Workloads</h2>
    <table>
        <tr>
            <th>Assistant Name</th>
            <th>Score</th>
            <th>Percentage</th>
        </tr>
        <?php foreach ($workloads as $workload): ?>
            <tr>
                <td><?= htmlspecialchars($workload['AssistantName']); ?></td>
                <td><?= htmlspecialchars($workload['AssistantScore']); ?></td>
                <td><?= number_format($workload['Percentage'], 2) . '%'; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
