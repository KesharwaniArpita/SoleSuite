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
    <title>SoleSuite - User Registration</title>
    <link rel="stylesheet" href="../css/form.css">
</head>
<body>
    <header class="main-header">
        <div class="header-content">
            <div class="logo">
                <img src="logo.png" alt="Company Logo">
            </div>
            <div class="admin-info">
                <span>Welcome admin</span>
                <img src="admin-icon.png" alt="Admin Icon" class="admin-icon">
            </div>
        </div>
    </header>
    <hr>

    <div class="container">
        <aside class="sidebar">
            <ul>
                <li><a href="../customer/CRM.php"><img src="..\\img\\i1.png" alt="Customer Manage Icon"><span>Customer Manage</span></a></li>
                <li><a href="../product/PM.php"><img src="..\\img\\i2.png" alt="Product Manage Icon"><span>Product Manage</span></a></li>
                <li><a href="../supplier/SCM.php"><img src="..\\img\\i1.png" alt="Supplier Manage Icon"><span>Supplier Manage</span></a></li>
                <li><a href="../inventory/IM.php"><img src="..\\img\\i1.png" alt="Inventory Manage Icon"><span>Inventory Manage</span></a></li>
                <!-- <!-- <li><a href="../user/UM.php"><img src="../img/i1.png" alt="User Manage Icon"><span>User Manage</span></a></li> --> -->
                <li><a href="../report/report.php"><img src="..\\img\\i3.png" alt="Report Icon"><span>Report</span></a></li>
                <li><a href="../Logout.php"><img src="..\\img\\i4.png" alt="Logout Icon"><span>Logout</span></a></li>
            </ul>
        </aside>

        <main>
            <header class="top-bar">
                <div class="user-info">
                    <button class="add-btn">ADD</button>
                </div>
                <div class="search-bar">
                    <input type="text" placeholder="Search">
                    <button>Search</button>
                </div>
            </header>
            <section class="registration-form">
                <div class="form-header">
                    <h2 style="text-align: center;">USER REGISTRATION FORM</h2>
                </div>

                <form method="POST">
                    <label>Full Name</label>
                    <input type="text" name="full_name" placeholder="Enter full name" required>

                    <label>Username</label>
                    <input type="text" name="username" placeholder="Enter username" required>

                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter password" required>

                    <label>Email</label>
                    <input type="email" name="email" placeholder="Enter email" required>

                    <label>Role</label>
                    <select name="role" required>
                        <option value="">Select role</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>

                    <label>Phone Number</label>
                    <input type="text" name="phone_number" placeholder="Enter phone number" required>

                    <label>Status</label>
                    <select name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>

                    <div class="form-buttons">
                        <!-- Close button now redirects to UM.php -->
                        <button type="button" class="close-btn" onclick="window.location.href='UM.php';">CLOSE</button>
                        <button type="submit" class="register-btn">REGISTER</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>

<?php
$conn->close();
?>
