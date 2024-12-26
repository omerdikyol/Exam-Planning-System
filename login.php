<?php
session_start();  // Start or resume a session
require 'config.php';  // Include your database configuration file

// Check if the user is already logged in
if (isset($_SESSION['userId'])) {
    header('Location: dashboard.php');  // Redirect to the welcome page if already logged in
    exit;
}

// Initialize an error message variable
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // Prepare a select statement to retrieve hashed password from the database
        $stmt = $conn->prepare("SELECT EmployeeID, Username, Password, Role, Name, departmentId FROM Employee WHERE Username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($user = $result->fetch_assoc()) {
            // Verify the hashed password with the password entered
            if (password_verify($password, $user['Password'])) {
                // Set session variables
                $_SESSION['userId'] = $user['EmployeeID'];
                $_SESSION['username'] = $user['Username'];
                $_SESSION['role'] = $user['Role'];
                $_SESSION['departmentId'] = $user['departmentId'];
                $_SESSION['name'] = $user['Name'];

                // Redirect to the welcome page
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid password!';
            }
        } else {
            $error = 'No user found with that username!';
        }
        $stmt->close();
    } else {
        $error = 'Please fill in all fields!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
