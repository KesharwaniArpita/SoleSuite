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
    $product_name = $_POST['product_name'] ?? '';
    $sku = $_POST['sku'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $cost_per_unit = $_POST['cost_per_unit'] ?? '';
    $supplier = $_POST['supplier'] ?? '';
    $reorder_level = $_POST['reorder_level'] ?? '';
    $warehouse_location = $_POST['warehouse_location'] ?? '';

    // Insert into the database
    $insert_sql = "INSERT INTO inventory (product_name, sku, quantity, cost_per_unit, supplier, reorder_level, warehouse_location)
                   VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ssissss", $product_name, $sku, $quantity, $cost_per_unit, $supplier, $reorder_level, $warehouse_location);

    if ($stmt->execute()) {
        // Redirect to inventory management page after successful registration
        header("Location: im.php");
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
    <title>SoleSuite - Inventory Registration</title>
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
                    <h2 style="text-align: center;">INVENTORY REGISTRATION FORM</h2>
                </div>

                <form method="POST">
                    <label>Product Name</label>
                    <input type="text" name="product_name" placeholder="Enter product name" required>

                    <label>SKU (Stock Keeping Unit)</label>
                    <input type="text" name="sku" placeholder="Enter SKU code" required>

                    <label>Quantity</label>
                    <input type="number" name="quantity" placeholder="Enter quantity in stock" required>

                    <label>Cost per Unit</label>
                    <input type="number" step="0.01" name="cost_per_unit" placeholder="Enter cost per unit" required>

                    <label>Supplier</label>
                    <input type="text" name="supplier" placeholder="Enter supplier name" required>

                    <label>Reorder Level</label>
                    <input type="number" name="reorder_level" placeholder="Enter reorder level" required>

                    <label>Warehouse Location</label>
                    <input type="text" name="warehouse_location" placeholder="Enter warehouse location" required>

                    <div class="form-buttons">
                        <button type="button" class="close-btn" onclick="window.location.href='im.php';">CLOSE</button>
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
