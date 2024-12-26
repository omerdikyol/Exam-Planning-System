<?php
include 'config.php';

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['userId']) || $_SESSION['role'] != 'Head of Secretary') {
    header('Location: login.php');  // Redirect if no session or wrong role
    exit();
}

// Get FacultyID from the DepartmentID stored in the session
$departmentId = $_SESSION['departmentId'];
$facultyQuery = "SELECT FacultyID FROM Department WHERE DepartmentID = ?";
$facultyStmt = $conn->prepare($facultyQuery);
$facultyStmt->bind_param("i", $departmentId);
$facultyStmt->execute();
$facultyResult = $facultyStmt->get_result();
$facultyData = $facultyResult->fetch_assoc();
$facultyId = $facultyData['FacultyID'];
$facultyStmt->close();

// Fetch all departments under the faculty
$departmentsQuery = "SELECT DepartmentID, DepartmentName FROM Department WHERE FacultyID = ?";
$stmt = $conn->prepare($departmentsQuery);
$stmt->bind_param("i", $facultyId);
$stmt->execute();
$departmentsResult = $stmt->get_result();

// Fetch courses within the faculty's departments for the first form
$courseQuery1 = "
    SELECT CourseID, Name, DepartmentID 
    FROM Course 
    WHERE DepartmentID IN (SELECT DepartmentID FROM Department WHERE FacultyID = ?)";
$courseStmt1 = $conn->prepare($courseQuery1);
$courseStmt1->bind_param("i", $facultyId);
$courseStmt1->execute();
$courseResult1 = $courseStmt1->get_result();

// Fetch courses within the faculty's departments for the second form
$courseQuery2 = "
    SELECT CourseID, Name, DepartmentID 
    FROM Course 
    WHERE DepartmentID IN (SELECT DepartmentID FROM Department WHERE FacultyID = ?)";
$courseStmt2 = $conn->prepare($courseQuery2);
$courseStmt2->bind_param("i", $facultyId);
$courseStmt2->execute();
$courseResult2 = $courseStmt2->get_result();

// Form submission for adding new courses
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['courseCode'], $_POST['courseName'], $_POST['departmentId'])) {
    $courseCode = $_POST['courseCode'];
    $courseName = $_POST['courseName'];
    $departmentId = $_POST['departmentId'];

    // Insert the new course
    $insertCourseSql = $conn->prepare("INSERT INTO Course (Code, Name, DepartmentID) VALUES (?, ?, ?)");
    $insertCourseSql->bind_param("ssi", $courseCode, $courseName, $departmentId);
    if ($insertCourseSql->execute()) {
        $_SESSION['feedback'] = "New course added successfully.";
    } else {
        $_SESSION['feedback'] = "Error adding course: " . $conn->error;
    }
    $insertCourseSql->close();
    header('Location: dashboard.php');
    exit();
}

// Form submission for adding exams and assigning assistants
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['courseId'], $_POST['examDate'], $_POST['startTime'], $_POST['endTime'], $_POST['assistantsNeeded'])) {
    $courseId = $_POST['courseId'];
    $examDate = $_POST['examDate'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $numberOfClasses = $_POST['numberOfClasses'];
    $assistantsNeeded = $_POST['assistantsNeeded'];

    // Get the department ID for the selected course
    $deptQuery = $conn->prepare("SELECT DepartmentID FROM Course WHERE CourseID = ?");
    $deptQuery->bind_param("i", $courseId);
    $deptQuery->execute();
    $deptResult = $deptQuery->get_result();
    $deptData = $deptResult->fetch_assoc();
    $courseDepartmentId = $deptData['DepartmentID'];
    $deptQuery->close();

    // Insert the new exam
    $insertSql = $conn->prepare("INSERT INTO Exam (CourseID, ExamDate, StartTime, EndTime, NumberOfClasses, AssistantsNeeded) VALUES (?, ?, ?, ?, ?, ?)");
    $insertSql->bind_param("isssii", $courseId, $examDate, $startTime, $endTime, $numberOfClasses, $assistantsNeeded);
    if ($insertSql->execute()) {
        $examId = $insertSql->insert_id;

        // Assign assistants from the specific course's department
        $assignSql = "SELECT EmployeeID, Name FROM Employee WHERE Role = 'Assistant' AND DepartmentID = ? ORDER BY AssistantScore ASC";
        $assignStmt = $conn->prepare($assignSql);
        $assignStmt->bind_param("i", $courseDepartmentId);
        $assignStmt->execute();
        $assistantResult = $assignStmt->get_result();

        $assignedAssistants = 0;
        while ($assistant = $assistantResult->fetch_assoc()) {
            if ($assignedAssistants >= $assistantsNeeded) {
                break;
            }

            $assistantId = $assistant['EmployeeID'];
            $assistantName = $assistant['Name'];

            // Check for conflicts
            $conflictCheckSql = "
                SELECT COUNT(*) as conflict_count 
                FROM Assignment a
                JOIN CourseSchedule cs ON a.ScheduleID = cs.ScheduleID
                WHERE a.AssistantID = ? 
                AND cs.Date = ?
                AND ((cs.StartTime <= ? AND cs.EndTime > ?) OR (cs.StartTime >= ? AND cs.StartTime < ?))
                UNION
                SELECT COUNT(*) as conflict_count 
                FROM Assignment a
                JOIN Exam e ON a.ExamID = e.ExamID
                WHERE a.AssistantID = ?
                AND e.ExamDate = ?
                AND ((e.StartTime <= ? AND e.EndTime > ?) OR (e.StartTime >= ? AND e.StartTime < ?))";

            $conflictStmt = $conn->prepare($conflictCheckSql);
            $conflictStmt->bind_param("issssissssss", $assistantId, $examDate, $startTime, $startTime, $startTime, $endTime, $assistantId, $examDate, $startTime, $startTime, $startTime, $endTime);
            $conflictStmt->execute();
            $conflictResult = $conflictStmt->get_result();
            $conflictData = $conflictResult->fetch_assoc();

            if ($conflictData['conflict_count'] == 0) {
                // No conflicts, proceed to assign
                // Update assistant score
                $updateScoreSql = "UPDATE Employee SET AssistantScore = AssistantScore + 1 WHERE EmployeeID = ?";
                $updateScoreStmt = $conn->prepare($updateScoreSql);
                $updateScoreStmt->bind_param("i", $assistantId);
                $updateScoreStmt->execute();

                // Link the assistant to the exam
                $linkExamSql = "INSERT INTO Assignment (ExamID, AssistantID, ScheduleID) VALUES (?, ?, NULL)";
                $linkExamStmt = $conn->prepare($linkExamSql);
                $linkExamStmt->bind_param("ii", $examId, $assistantId);
                $linkExamStmt->execute();

                $assignedAssistants++;
            } else {
                $_SESSION['feedback'] .= "Assistant " . htmlspecialchars($assistantName) . " has a conflict and cannot be assigned.<br>";
            }
        }

        if ($assignedAssistants == $assistantsNeeded) {
            $_SESSION['feedback'] = "New exam added and assistants assigned successfully.";
        } else {
            $_SESSION['feedback'] .= " Not all requested assistants could be assigned.";
        }
    } else {
        $_SESSION['feedback'] = "Error adding exam: " . $conn->error;
    }
    $insertSql->close();
    header('Location: dashboard.php');
    exit();
}

// Form submission for adding course schedules
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['courseId'], $_POST['scheduleDate'], $_POST['startTime'], $_POST['endTime'])) {
    $courseId = $_POST['courseId'];
    $scheduleDate = $_POST['scheduleDate'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];

    // Insert the new course schedule
    $insertScheduleSql = $conn->prepare("INSERT INTO CourseSchedule (CourseID, Date, StartTime, EndTime) VALUES (?, ?, ?, ?)");
    $insertScheduleSql->bind_param("isss", $courseId, $scheduleDate, $startTime, $endTime);
    if ($insertScheduleSql->execute()) {
        $_SESSION['feedback'] = "New course schedule added successfully.";
    } else {
        $_SESSION['feedback'] = "Error adding course schedule: " . $conn->error;
    }
    $insertScheduleSql->close();
    header('Location: dashboard.php');
    exit();
}

// Fetch all assistant scores for display
$scoreSql = "
    SELECT EmployeeID, AssistantScore, Name 
    FROM Employee 
    WHERE Role = 'Assistant' 
    AND DepartmentID IN (SELECT DepartmentID FROM Department WHERE FacultyID = ?) 
    ORDER BY AssistantScore DESC";
$scoreStmt = $conn->prepare($scoreSql);
$scoreStmt->bind_param("i", $facultyId);
$scoreStmt->execute();
$scoresResult = $scoreStmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Head of Secretary Dashboard</title>
</head>
<body>
    <?php
    if (isset($_SESSION['feedback'])) {
        echo "<p>" . $_SESSION['feedback'] . "</p>";
        unset($_SESSION['feedback']); // Clear the feedback after displaying it
    }
    ?>
    <h2>Add a New Course</h2>
    <form method="post" action="">
        <label for="departmentId">Department:</label>
        <select name="departmentId" id="departmentId" required>
            <?php while ($department = $departmentsResult->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($department['DepartmentID']); ?>"><?= htmlspecialchars($department['DepartmentName']); ?></option>
            <?php endwhile; ?>
        </select><br><br>
        <label for="courseCode">Course Code:</label>
        <input type="text" name="courseCode" id="courseCode" required><br>
        <label for="courseName">Course Name:</label>
        <input type="text" name="courseName" id="courseName" required><br>
        <input type="submit" value="Add Course">
    </form>

    <h2>Add an Exam</h2>
    <form method="post" action="">
        <label for="courseId">Course:</label>
        <select name="courseId" id="courseId" required>
            <?php while ($course = $courseResult1->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($course['CourseID']); ?>"><?= htmlspecialchars($course['Name']); ?></option>
            <?php endwhile; ?>
        </select><br><br>
        <label for="examDate">Exam Date:</label>
        <input type="date" name="examDate" required><br>
        <label for="startTime">Start Time:</label>
        <input type="time" name="startTime" required><br>
        <label for="endTime">End Time:</label>
        <input type="time" name="endTime" required><br>
        <label for="numberOfClasses">Number Of Classes:</label>
        <input type="number" name="numberOfClasses" min="1" max="10" required><br>
        <label for="assistantsNeeded">Assistants Needed:</label>
        <input type="number" name="assistantsNeeded" min="1" max="10" required><br>
        <input type="submit" value="Add Exam">
    </form>

    <h2>Add a Course Schedule</h2>
    <form method="post" action="">
        <label for="courseId">Course:</label>
        <select name="courseId" id="courseId">
            <?php while ($course = $courseResult2->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($course['CourseID']); ?>"><?= htmlspecialchars($course['Name']); ?></option>
            <?php endwhile; ?>
        </select><br><br>
        <label for="scheduleDate">Schedule Date:</label>
        <input type="date" name="scheduleDate" required><br>
        <label for="startTime">Start Time:</label>
        <input type="time" name="startTime" required><br>
        <label for="endTime">End Time:</label>
        <input type="time" name="endTime" required><br>
        <input type="submit" value="Add Schedule">
    </form>

    <h3>Assistant Scores</h3>
    <table border="1">
        <tr>
            <th>Employee ID</th>
            <th>Name</th>
            <th>Score</th>
        </tr>
        <?php while ($score = $scoresResult->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($score['EmployeeID']); ?></td>
                <td><?= htmlspecialchars($score['Name']); ?></td>
                <td><?= htmlspecialchars($score['AssistantScore']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>