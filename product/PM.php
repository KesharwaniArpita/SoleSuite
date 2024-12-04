<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Update if needed
$dbname = "solesuite"; // Ensure this database exists

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products from the database
$sql = "SELECT id, name, type, style, price, color, size, brand, supplier_name FROM products";
$result = $conn->query($sql);

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Prepare delete query
    $delete_sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "Product deleted successfully!";
    } else {
        echo "Error deleting product.";
    }
    $stmt->close();
    exit; // Stop further execution after deletion
}

// Handle update request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $update_id = intval($_POST['update_id']);
    $name = trim($_POST['name'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $style = trim($_POST['style'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $color = trim($_POST['color'] ?? '');
    $size = trim($_POST['size'] ?? '');
    $brand = trim($_POST['brand'] ?? '');
    $supplier_name = trim($_POST['supplier_name'] ?? '');

    // Validate required fields
    if (empty($update_id) || empty($name) || empty($type) || empty($price)) {
        http_response_code(400);
        echo "Required fields are missing.";
        exit;
    }

    // Prepare and execute update statement
    $update_sql = "UPDATE products SET name = ?, type = ?, style = ?, price = ?, color = ?, size = ?, brand = ?, supplier_name = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    if (!$stmt) {
        error_log("SQL Preparation Error: " . $conn->error);
        http_response_code(500);
        echo "Server error during update preparation.";
        exit;
    }

    $stmt->bind_param("ssssssssi", $name, $type, $style, $price, $color, $size, $brand, $supplier_name, $update_id);

    if ($stmt->execute()) {
        echo "Product updated successfully!";
    } else {
        error_log("SQL Execution Error: " . $stmt->error);
        http_response_code(500);
        echo "Error updating product.";
    }
    $stmt->close();
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoleSuite - Product Management</title>
    <link rel="stylesheet" href="../css/form.css">
    <style>
        .clickable-row {
            cursor: pointer;
        }
        .clickable-row:hover {
            background-color: #f0f0f0;
        }
        .form-container {
            display: none;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
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
                <li><a href="../customer/CRM.php"><img src="../img/i1.png" alt="Customer Manage Icon"><span>Customer Manage</span></a></li>
                <li><a href="../product/PM.php"><img src="../img/i2.png" alt="Product Manage Icon"><span>Product Manage</span></a></li>
                <li><a href="../supplier/SCM.php"><img src="../img/i1.png" alt="Supplier Manage Icon"><span>Supplier Manage</span></a></li>
                <li><a href="../inventory/IM.php"><img src="../img/i1.png" alt="Inventory Manage Icon"><span>Inventory Manage</span></a></li>
                <!-- <li><a href="../user/UM.php"><img src="../img/i1.png" alt="User Manage Icon"><span>User Manage</span></a></li> -->
                <li><a href="../report/report.php"><img src="../img/i3.png" alt="Report Icon"><span>Report</span></a></li>
                <li><a href="../Logout.php"><img src="../img/i4.png" alt="Logout Icon"><span>Logout</span></a></li>
            </ul>
        </aside>

        <main>
            <header class="top-bar">
                <div class="user-info">
                    <a href="PMform.php">
                        <button class="add-btn">ADD</button>
                    </a>
                </div>
                
            </header>

            <!-- Product List -->
            <section class="product-list" align = "center">
                <h2>Product List</h2>
                <style>
        /* Section and heading styling */
        .record-list h2 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
            text-align: left;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff; /* Table background color */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow for the table */
            border-radius: 8px; /* Optional: Rounded corners for table */
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            font-size: 1rem;
            border: 1px solid #DAE0DB;
            color: #555;
        }

        table th {
            background-color: #f1f1f1; /* Light background for table headers */
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #333;
        }

        table tbody tr:hover {
            background-color: #f9f9f9; /* Light gray background on row hover */
            cursor: pointer;
        }

        .clickable-row:hover {
            background-color: #91BDE5; /* Blue highlight on hover */
            color: white;
        }

        /* Styling for "No records found" message */
        table tr td[colspan="3"] {
            text-align: center;
            font-size: 1.2rem;
            color: #999;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            table th, table td {
                font-size: 0.9rem;
                padding: 8px;
            }

            .record-list h2 {
                font-size: 1.5rem;
            }
        }
    </style>
                <table border="1" cellspacing="0" cellpadding="10">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Style</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($result && $result->num_rows > 0) { 
                            while ($row = $result->fetch_assoc()) { 
                                echo '<tr class="clickable-row" 
                                          data-id="' . $row['id'] . '" 
                                          data-name="' . $row['name'] . '" 
                                          data-type="' . $row['type'] . '" 
                                          data-style="' . $row['style'] . '" 
                                          data-price="' . $row['price'] . '" 
                                          data-color="' . $row['color'] . '" 
                                          data-size="' . $row['size'] . '" 
                                          data-brand="' . $row['brand'] . '" 
                                          data-supplier_name="' . $row['supplier_name'] . '">';
                                echo '<td>' . $row['name'] . '</td>';
                                echo '<td>' . $row['type'] . '</td>';
                                echo '<td>' . $row['style'] . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo "<tr><td colspan='3'>No products found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>

            <!-- Product Details Form -->
            <section class="form-container" id="product-form">
                <div class="form-header">
                    <h2 id="product-title">Product Information</h2>
                </div>
                <form id="update-form">
                    <label>Name</label>
                    <input type="text" id="name" name="name" value="" required>
                    
                    <label>Type</label>
                    <input type="text" id="type" name="type" value="" required>
                    
                    <label>Style</label>
                    <input type="text" id="style" name="style" value="" required>

                    <label>Price</label>
                    <input type="text" id="price" name="price" value="" required>
                    
                    <label>Color</label>
                    <input type="text" id="color" name="color" value="" required>
                    
                    <label>Size</label>
                    <input type="text" id="size" name="size" value="" required>
                    
                    <label>Brand</label>
                    <input type="text" id="brand" name="brand" value="" required>
                    
                    <label>Supplier Name</label>
                    <input type="text" id="supplier_name" name="supplier_name" value="" required>

                    <div class="form-buttons">
                        <button type="button" class="close-btn" onclick="closeForm()">CLOSE</button>
                        <button type="button" class="register-btn" onclick="updateProduct()">UPDATE</button>
                        <button type="button" class="register-btn" style="background-color: red;" onclick="deleteProduct()">DELETE</button>
                    </div>
                </form>
            </section>
        </main>
    </div>

    <script>
        // JavaScript function to show the form and hide the product list
        document.querySelectorAll('.clickable-row').forEach(row => {
            row.addEventListener('click', function() {
                // Hide the product list
                document.querySelector('.product-list').style.display = 'none';
                document.querySelector('.top-bar').style.display = 'none';

                // Show the form container
                document.querySelector('.form-container').style.display = 'block';

                // Populate form with clicked row data
                document.getElementById('name').value = this.getAttribute('data-name');
                document.getElementById('type').value = this.getAttribute('data-type');
                document.getElementById('style').value = this.getAttribute('data-style');
                document.getElementById('price').value = this.getAttribute('data-price');
                document.getElementById('color').value = this.getAttribute('data-color');
                document.getElementById('size').value = this.getAttribute('data-size');
                document.getElementById('brand').value = this.getAttribute('data-brand');
                document.getElementById('supplier_name').value = this.getAttribute('data-supplier_name');

                // Set the product ID in the form's data-id
                document.querySelector('.form-container').setAttribute('data-id', this.getAttribute('data-id'));

                // Set the product title
                document.getElementById('product-title').textContent = 'Product: ' + this.getAttribute('data-name');
            });
        });

        // Function to close the form
        function closeForm() {
            document.querySelector('.product-list').style.display = 'block';
            document.querySelector('.top-bar').style.display = 'flex';
            document.querySelector('.form-container').style.display = 'none';
        }

        // Function to delete a product
        function deleteProduct() {
            const productId = document.querySelector('.form-container').getAttribute('data-id'); // Get the product ID from the form's data-id

            if (confirm('Are you sure you want to delete this product?')) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "", true); // Send POST request to the same page
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                // Set up the response handler
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        alert("Product deleted successfully!");
                        location.reload();  // Reload page after deletion
                    } else {
                        alert("Error deleting product.");
                    }
                };

                // Send the delete ID as a POST parameter
                xhr.send("delete_id=" + productId);
            }
        }

        // Function to update a product
        function updateProduct() {
            const productId = document.querySelector('.form-container').getAttribute('data-id'); // Get the product ID from the form's data-id

            if (!productId) {
                alert("No product selected!");
                return;
            }

            // Collect form data
            const params = new URLSearchParams();
            params.append('update_id', productId);
            params.append('name', document.getElementById('name').value.trim());
            params.append('type', document.getElementById('type').value.trim());
            params.append('style', document.getElementById('style').value.trim());
            params.append('price', document.getElementById('price').value.trim());
            params.append('color', document.getElementById('color').value.trim());
            params.append('size', document.getElementById('size').value.trim());
            params.append('brand', document.getElementById('brand').value.trim());
            params.append('supplier_name', document.getElementById('supplier_name').value.trim());

            // Confirm before update
            if (!confirm("Are you sure you want to update this product?")) {
                return;
            }

            // AJAX Request to update product
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "", true); // Send POST request to the same page
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert("Product updated successfully!");
                    location.reload(); // Reload the page to reflect updated data
                } else {
                    alert("Error updating product: " + xhr.responseText);
                    console.error("Server Error:", xhr.responseText);
                }
            };

            xhr.send(params.toString());
        }


    </script>
</body>
</html>
