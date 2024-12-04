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

// Fetch suppliers
$suppliers = [];
$sql_suppliers = "SELECT DISTINCT supplier_name FROM supply"; 
$suppliers = $conn->query($sql_suppliers);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $color = $_POST['color'] ?? '';
    $size = $_POST['size'] ?? '';
    $type = $_POST['type'] ?? '';
    $style = $_POST['style'] ?? '';
    $brand = $_POST['brand'] ?? '';
    $supplier = $_POST['supplier'] ?? '';

    // Insert into the database
    $insert_sql = "INSERT INTO products (name, price, color, size, type, style, brand, supplier_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("sssssssi", $name, $price, $color, $size, $type, $style, $brand, $supplier);

    if ($stmt->execute()) {
        echo "Product registered successfully!";
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
    <title>SoleSuite - Product Registration</title>
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
                <!--  <li><a href="../user/UM.php"><img src="../img/i1.png" alt="User Manage Icon"><span>User Manage</span></a></li> -->
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
                    <h2 style="text-align: center;">PRODUCT REGISTRATION FORM</h2>
                </div>

                <form method="POST">
                    <label>Name</label>
                    <input type="text" name="name" placeholder="Enter name" required>

                    <label>Price</label>
                    <input type="text" name="price" placeholder="Enter price" required>

                    <label>Color</label>
                    <input type="text" name="color" placeholder="Enter color" required>

                    <label>Size</label>
                    <input type="text" name="size" placeholder="Enter size" required>

                    <label>Type</label>
                    <input type="text" name="type" placeholder="Enter type" required>

                    <label>Style</label>
                    <input type="text" name="style" placeholder="Enter style" required>

                    <label>Brand</label>
                    <input type="text" name="brand" placeholder="Enter brand" required>

                    <label>Supplier</label>
                    <select name="supplier" required>
                        <option value="">Select supplier</option>
                        <?php if ($suppliers): ?>
                            <?php while ($row = $suppliers->fetch_assoc()): ?>
                                <option value="<?= $row['supplier_name'] ?>"><?= $row['supplier_name'] ?></option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>

                    <div class="form-buttons">
                    <button type="button" class="close-btn" onclick="window.location.href='PM.php';">CLOSE</button>
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
