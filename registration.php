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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $email = $_POST['email'] ?? '';
    $role = $_POST['role'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $status = $_POST['status'] ?? '';

    // Hash the password before saving it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into the database
    $insert_sql = "INSERT INTO users (full_name, username, password, email, role, phone_number, status)
                   VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("sssssss", $full_name, $username, $hashed_password, $email, $role, $phone_number, $status);

    if ($stmt->execute()) {
        // Redirect to user management page after successful registration
        header("Location: UM.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin User Registration</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/form.css">
    <style>

        .login-container {
            width: 100%;
            max-width: 1000px;
            height: 750px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            
        }
       

        .login-form {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .login-form h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .login-form label {
            margin-top: 15px;
            font-weight: bold;
            color: #444;
        }

       

        .form-buttons button {
            width: 36%;
        }

        

        .close-btn {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2>User Registration Form</h2>
            <form method="POST">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" placeholder="Enter full name" required>

                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter email" required>

                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="">Select role</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>

                <label for="phone_number">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" placeholder="Enter phone number" required>

                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>

                <div class="form-buttons">
                    <button type="button" class="close-btn" onclick="window.location.href='Landing_page.php';">CLOSE</button>
                    <button type="submit" class="register-btn">REGISTER</button>
                </div>
            </form>
            <div class="extra-options">
                <a href="forpass.php">Forgot your Password?</a>
                <a href="help.php">Get help Signed in</a>
            </div>
            <div style="padding-top: 50px;">
                <a href="help.php">Terms of use</a> | <a href="help.php">Privacy policy</a>
            </div>
        </div>
    </div>
</body>
</html>


<?php
$conn->close();
?>
