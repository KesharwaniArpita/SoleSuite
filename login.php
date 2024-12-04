<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Update if needed
$dbname = "solesuite"; // Ensure this database exists

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message variable
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check if username exists
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Username does not exist
        $message = "User does not exist.";
    } else {
        // Fetch user data
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Password matches, redirect to reports.php
            header("Location: report/report.php");
            exit();
        } else {
            // Password does not match
            $message = "Wrong password.";
        }
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2>Login as an Admin User</h2>
            <!-- Display message -->
            <?php if (!empty($message)): ?>
                <div class="error-message" style="color: red; margin-bottom: 15px;">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            <form method="POST">
                <label for="login"></label>
                <input type="text" id="login" name="login" placeholder="Login" required>
                <label for="password"></label>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <button type="submit">LOGIN</button>
            </form>
            <div class="extra-options">
                <a href="registration.php">Not an existing user? Register Here</a>
                <a href="help.php">Get help Signed in</a>
            </div>
            <div style="padding-top: 50px;">
                <a href="help.php">Terms of use</a> | <a href="help.php">Privacy policy</a>
            </div>
        </div>
        <div class="login-image">
            <img src="img/p1.png" alt="SoleSuite Sneaker">
            <img src="img/logo.png" alt="Company Logo">
        </div>
    </div>
</body>
</html>
