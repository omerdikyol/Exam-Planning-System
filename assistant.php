<?php
include 'config.php';

if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is logged in and is an assistant
if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'Assistant') {
    header('Location: login.php'); // Redirect if not authenticated or not an assistant
    exit();
}

$departmentId = $_SESSION['departmentId'];
$userId = $_SESSION['userId'];

$feedback = "";

// Handle selected courses (user selection)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_schedules'])) {
    $selectedSchedules = $_POST['selected_schedules'];
    $addedSchedules = [];

    foreach ($selectedSchedules as $scheduleID) {
        // Check for existing assignments to prevent conflicts
        $conflictCheckSql = "
            SELECT COUNT(*) as conflict_count 
            FROM Assignment a
            JOIN CourseSchedule cs ON a.ScheduleID = cs.ScheduleID
            WHERE a.AssistantID = ? 
            AND cs.ScheduleID = ?
            AND cs.Date = (SELECT Date FROM CourseSchedule WHERE ScheduleID = ?)
            AND cs.StartTime = (SELECT StartTime FROM CourseSchedule WHERE ScheduleID = ?)";
        
        $conflictStmt = $conn->prepare($conflictCheckSql);
        if (!$conflictStmt) {
            $_SESSION['feedback'] = "Conflict check prepare failed: " . $conn->error;
            header('Location: dashboard.php');
            exit();
        }
        $conflictStmt->bind_param("iiii", $userId, $scheduleID, $scheduleID, $scheduleID);
        $conflictStmt->execute();
        $conflictResult = $conflictStmt->get_result();
        $conflictData = $conflictResult->fetch_assoc();

        if ($conflictData['conflict_count'] == 0) {
            // No conflicts, proceed to insert
            $assignmentSql = "INSERT IGNORE INTO Assignment (ScheduleID, AssistantID) VALUES (?, ?)";
            $assignStmt = $conn->prepare($assignmentSql);
            if (!$assignStmt) {
                $_SESSION['feedback'] = "Assignment insert prepare failed: " . $conn->error;
                header('Location: dashboard.php');
                exit();
            }
            $assignStmt->bind_param("ii", $scheduleID, $userId);
            if ($assignStmt->execute()) {
                $addedSchedules[] = $scheduleID;
            } else {
                $feedback .= "Failed to add schedule ID: $scheduleID. Error: " . $assignStmt->error . "<br>";
            }
        } else {
            $feedback .= "Schedule ID: $scheduleID has conflicts.<br>";
        }
    }

    if (!empty($addedSchedules)) {
        $feedback .= "Selected schedules were successfully added.<br>";
    } else {
        $feedback .= "No schedules were added due to conflicts or errors.<br>";
    }

    // Store feedback in session and redirect to dashboard.php
    $_SESSION['feedback'] = $feedback;
    header('Location: dashboard.php');
    exit();
}

// Fetch courses within the assistant's department
$courseQuery = "
    SELECT cs.ScheduleID, c.CourseID, c.Code, c.Name, cs.Date, cs.StartTime, cs.EndTime
    FROM Course c
    JOIN CourseSchedule cs ON c.CourseID = cs.CourseID
    WHERE c.DepartmentID = ?";
$stmt = $conn->prepare($courseQuery);
if (!$stmt) {
    $_SESSION['feedback'] = "Prepare failed: " . $conn->error;
    header('Location: dashboard.php');
    exit();
}
$stmt->bind_param("i", $departmentId);
$stmt->execute();
$courseResult = $stmt->get_result();
if (!$courseResult) {
    $_SESSION['feedback'] = "SQL error: " . $conn->error;
    header('Location: dashboard.php');
    exit();
}

// Fetch the assistant's weekly plan for courses and exams
$weeklyPlanQuery = "
    SELECT cs.Date, cs.StartTime, cs.EndTime, c.Name AS CourseName, 'Course' AS Type
    FROM Assignment a
    JOIN CourseSchedule cs ON a.ScheduleID = cs.ScheduleID
    JOIN Course c ON cs.CourseID = c.CourseID
    WHERE a.AssistantID = ?
    UNION
    SELECT e.ExamDate AS Date, e.StartTime AS StartTime, e.EndTime AS EndTime, c.Name AS CourseName, 'Exam' AS Type
    FROM Exam e
    JOIN Course c ON e.CourseID = c.CourseID
    WHERE e.ExamID IN (SELECT ExamID FROM Assignment WHERE AssistantID = ?)
    ORDER BY Date, StartTime";
$weeklyPlanStmt = $conn->prepare($weeklyPlanQuery);
if (!$weeklyPlanStmt) {
    $_SESSION['feedback'] = "Prepare failed: " . $conn->error;
    header('Location: dashboard.php');
    exit();
}
$weeklyPlanStmt->bind_param("ii", $userId, $userId);
$weeklyPlanStmt->execute();
$weeklyPlanResult = $weeklyPlanStmt->get_result();

// Prepare weekly plan data
$weeklyPlan = [];
if ($weeklyPlanResult) {
    while ($row = $weeklyPlanResult->fetch_assoc()) {
        $weeklyPlan[] = $row;
    }
}

// Days and timeslots for the weekly plan table
$daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']; 
$timeslots = [
    ['08:00', '08:30'],
    ['08:30', '09:00'],
    ['09:00', '09:30'],
    ['09:30', '10:00'],
    ['10:00', '10:30'],
    ['10:30', '11:00'],
    ['11:00', '11:30'],
    ['11:30', '12:00'],
    ['12:00', '12:30'],
    ['12:30', '13:00'],
    ['13:00', '13:30'],
    ['13:30', '14:00'],
    ['14:00', '14:30'],
    ['14:30', '15:00'],
    ['15:00', '15:30'],
    ['15:30', '16:00'],
    ['16:00', '16:30'],
    ['16:30', '17:00'],
    ['17:00', '17:30'],
    ['17:30', '18:00'],
    ['18:00', '18:30'],
    ['18:30', '19:00'],
    ['19:00', '19:30'],
    ['19:30', '20:00'],
    ['20:00', '20:30'],
    ['20:30', '21:00'],
    ['21:00', '21:30'],
    ['21:30', '22:00']
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assistant Dashboard</title>
</head>
<body>
    <?php
    if (isset($_SESSION['feedback'])) {
        echo "<p>" . $_SESSION['feedback'] . "</p>";
        unset($_SESSION['feedback']); // Clear the feedback after displaying it
    }
    ?>
    <form method="post" action="assistant.php">
        <label for="selected_schedules">Select Course Schedules:</label>
        <select id="selected_schedules" name="selected_schedules[]" multiple>
            <?php while ($course = $courseResult->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($course['ScheduleID']); ?>">
                    <?php echo htmlspecialchars($course['Code']) . " - " . htmlspecialchars($course['Name']) . " (" . htmlspecialchars($course['Date']) . " " . htmlspecialchars($course['StartTime']) . " - " . htmlspecialchars($course['EndTime']) . ")"; ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br><br>
        <input type="submit" value="Add Selected Schedules">
    </form>
    <br>
    <h2>Your Weekly Program</h2>
    <br>
    <table border="1">
        <tr>
            <th>Time/Day</th>
            <?php foreach ($daysOfWeek as $day): ?>
                <th><?php echo htmlspecialchars($day); ?></th>
            <?php endforeach; ?>
        </tr>
        <?php foreach ($timeslots as $timeslot): ?>
            <tr>
                <td><?php echo htmlspecialchars($timeslot[0] . "-" . $timeslot[1]); ?></td>
                <?php foreach ($daysOfWeek as $day): ?>
                    <td>
                        <?php
                        $scheduledItem = '';
                        foreach ($weeklyPlan as $plan) {
                            $planDate = date('l', strtotime($plan['Date']));
                            $planStartTime = strtotime($plan['StartTime']);
                            $planEndTime = strtotime($plan['EndTime']);
                            $timeslotStart = strtotime($timeslot[0]);
                            $timeslotEnd = strtotime($timeslot[1]);
                            if ($planDate == $day && (
                                ($planStartTime <= $timeslotStart && $planEndTime > $timeslotStart) || // starts before and ends during/after
                                ($planStartTime >= $timeslotStart && $planStartTime < $timeslotEnd) // starts during
                            )) {
                                $scheduledItem = htmlspecialchars($plan['CourseName']) . " (" . htmlspecialchars($plan['Type']) . ")";
                                break;
                            }
                        }
                        echo $scheduledItem;
                        ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <button onclick="location.reload();">Refresh Weekly Plan</button>
</body>
</html>
