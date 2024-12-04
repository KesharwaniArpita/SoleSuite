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
    $order_id = $_POST['order_id'] ?? '';
    $product_name = $_POST['product'] ?? '';
    $supplier_name = $_POST['supplier'] ?? '';
    $quantity_ordered = $_POST['quantity'] ?? '';
    $order_date = $_POST['order_date'] ?? '';
    $expected_delivery_date = $_POST['expected_date'] ?? '';
    $status = $_POST['status'] ?? '';
    $tracking_number = $_POST['tracking_number'] ?? '';

    // Insert into the database
    $insert_sql = "INSERT INTO supply (order_id, product_name, supplier_name, quantity_ordered, order_date, expected_delivery_date, status, tracking_number)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("sssissss", $order_id, $product_name, $supplier_name, $quantity_ordered, $order_date, $expected_delivery_date, $status, $tracking_number);

    if ($stmt->execute()) {
        // Redirect to scm.php after successful registration
        header("Location: scm.php");
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
    <title>SoleSuite - Supply Registration</title>
    <link rel="stylesheet" href="../css/form.css">
</head>
<body>
    <header class="main-header">
        <div class="header-content">
            <div class="logo">
                <img src="../img/logo.png" alt="Company Logo">
            </div>
            <div class="admin-info">
                <span>Welcome admin</span>
                <img src="../img/Admin icon.png" alt="Admin Icon" class="admin-icon">
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
                <!-- <li><a href="../user/UM.php"><img src="../img/i1.png" alt="User Manage Icon"><span>User Manage</span></a></li> -->
                <li><a href="../report/report.php"><img src="..\\img\\i3.png" alt="Report Icon"><span>Report</span></a></li>
                <li><a href="../Logout.php"><img src="..\\img\\i4.png" alt="Logout Icon"><span>Logout</span></a></li>
            </ul>
        </aside>

        <main>
            <!-- <header class="top-bar">
                <div class="user-info">
                    <button class="add-btn">ADD</button>
                </div>
                <div class="search-bar">
                    <input type="text" placeholder="Search">
                    <button>Search</button>
                </div>
            </header> -->
            <section class="registration-form">
                <div class="form-header">
                    <h2 style="text-align: center;">SUPPLY REGISTRATION FORM</h2>
                </div>

                <form method="POST">
                    <label>Order ID</label>
                    <input type="text" name="order_id" placeholder="Enter order ID" required>

                    <label>Product</label>
                    <input type="text" name="product" placeholder="Enter product name" required>

                    <label>Supplier</label>
                    <input type="text" name="supplier" placeholder="Enter supplier name" required>

                    <label>Quantity Ordered</label>
                    <input type="number" name="quantity" placeholder="Enter quantity ordered" required>

                    <label>Order Date</label>
                    <input type="date" name="order_date" required>

                    <label>Expected Delivery Date</label>
                    <input type="date" name="expected_date" required>

                    <label>Status</label>
                    <select name="status" required>
                        <option value="">Select status</option>
                        <option value="Pending">Pending</option>
                        <option value="Shipped">Shipped</option>
                        <option value="Delivered">Delivered</option>
                    </select>

                    <label>Tracking Number</label>
                    <input type="text" name="tracking_number" placeholder="Enter tracking number">

                    <div class="form-buttons">
                        <button type="button" class="close-btn" onclick="window.location.href='scm.php';">CLOSE</button>
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
