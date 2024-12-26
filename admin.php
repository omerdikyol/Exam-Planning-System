<?php
include 'config.php';
if (!isset($_SESSION)) {
    session_start();
}

// Define an array to hold departments and any potential error message
$departments = [];
$errorMessage = '';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php'); // Redirect if not authenticated or not an admin
    exit();
}

// Fetch departments from the database
$result = $conn->query("SELECT DepartmentID, DepartmentName FROM Department");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
} else {
    $errorMessage = "Failed to load departments: " . $conn->error;
}

// Fetch faculties from the database
$faculties = [];
$facultyResult = $conn->query("SELECT FacultyID, FacultyName FROM Faculty");
if ($facultyResult) {
    while ($row = $facultyResult->fetch_assoc()) {
        $faculties[] = $row;
    }
} else {
    $errorMessage = "Failed to load faculties: " . $conn->error;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $departmentID = $role == 'Dean' || $role == 'Admin' ? null : $_POST['department']; // Directly using the department ID from the form
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    if ($role != 'Dean' && $role != 'Admin' && empty($_POST['department'])) {
        $errorMessage = "Department is required for this role.";
    } elseif ($role == 'Dean' && empty($_POST['faculty'])) {
        $errorMessage = "Faculty is required for this role.";
    }

    // Prepare SQL statement to insert the new employee avoiding SQL injection
    $stmt = $conn->prepare("INSERT INTO Employee (Name, Role, Username, Password, DepartmentID) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $name, $role, $username, $hashed_password, $departmentID);

    // If there is an error message, display it and stop execution
    if ($errorMessage) {
        $_SESSION['feedback'] = $errorMessage;
        header('Location: dashboard.php');
        exit();
    }

    // Execute the query and check for success
    if ($stmt->execute()) {
        $feedback = "New employee added successfully.<br>";
        $last_id = $stmt->insert_id;

        // Check if role is Dean and update faculty
        if ($role === "Dean" && isset($_POST['faculty'])) {
            $facultyID = $_POST['faculty'];
            $updateFacultyStmt = $conn->prepare("UPDATE Faculty SET DeanID = ? WHERE FacultyID = ?");
            $updateFacultyStmt->bind_param("ii", $last_id, $facultyID);
            if ($updateFacultyStmt->execute()) {
                $feedback .= "Faculty dean updated successfully.<br>";
            } else {
                $feedback .= "Error updating faculty dean: " . $updateFacultyStmt->error . "<br>";
            }
            $updateFacultyStmt->close();
        }

        // Check if role is Head of Department and update department
        if ($role === "Head of Department" && isset($_POST['department'])) {
            $departmentID = $_POST['department'];
            $updateDepartmentStmt = $conn->prepare("UPDATE Department SET HeadID = ? WHERE DepartmentID = ?");
            $updateDepartmentStmt->bind_param("ii", $last_id, $departmentID);
            if ($updateDepartmentStmt->execute()) {
                $feedback .= "Department head updated successfully.<br>";
            } else {
                $feedback .= "Error updating department head: " . $updateDepartmentStmt->error . "<br>";
            }
            $updateDepartmentStmt->close();
        }

        // Store feedback in session and redirect to dashboard.php
        $_SESSION['feedback'] = $feedback;
        header('Location: dashboard.php');
        exit();
    } else {
        $_SESSION['feedback'] = "Error: " . $stmt->error;
        header('Location: dashboard.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Employee</title>
    <script>
        function showRoleSpecificFields() {
            var role = document.getElementById("role").value;
            var departmentField = document.getElementById("departmentField");
            var facultyField = document.getElementById("facultyField");

            // Display department field for certain roles
            if (role === "Secretary" || role === "Head of Department" || role === "Assistant" || role === "Head of Secretary") {
                departmentField.style.display = "block";
            } else {
                departmentField.style.display = "none";
            }

            // Display faculty field only for Deans
            facultyField.style.display = (role === "Dean") ? "block" : "none";
        }
    </script>
</head>
<body>
    <h1>Add New Employee</h1>
    <?php
    if (isset($_SESSION['feedback'])) {
        echo "<p style='color: green;'>" . $_SESSION['feedback'] . "</p>";
        unset($_SESSION['feedback']); // Clear the feedback after displaying it
    }
    ?>
    <form action="dashboard.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="role">Role:</label>
        <select id="role" name="role" onchange="showRoleSpecificFields()" required>
            <option value="">Select a Role</option>
            <option value="Assistant">Assistant</option>
            <option value="Secretary">Secretary</option>
            <option value="Head of Department">Head of Department</option>
            <option value="Head of Secretary">Head of Secretary</option>
            <option value="Dean">Dean</option>
            <option value="Admin">Admin</option>
        </select><br><br>

        <div id="facultyField" style="display: none;">
            <label for="faculty">Faculty:</label>
            <select id="faculty" name="faculty">
                <?php foreach ($faculties as $faculty): ?>
                    <option value="<?= htmlspecialchars($faculty['FacultyID']) ?>"><?= htmlspecialchars($faculty['FacultyName']) ?></option>
                <?php endforeach; ?>
            </select><br><br>
        </div>

        <div id="departmentField" style="display: none;">
            <label for="department">Department:</label>
            <select id="department" name="department">
                <?php foreach ($departments as $dept): ?>
                    <option value="<?= htmlspecialchars($dept['DepartmentID']) ?>"><?= htmlspecialchars($dept['DepartmentName']) ?></option>
                <?php endforeach; ?>
            </select><br><br>
        </div>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Add Employee">
    </form>
</body>
</html>
