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

// Fetch inventory records from the database
$sql = "SELECT id, product_name, sku, quantity, cost_per_unit, supplier, reorder_level, warehouse_location FROM inventory";
$result = $conn->query($sql);

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Prepare delete query
    $delete_sql = "DELETE FROM inventory WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "Record deleted successfully!";
    } else {
        echo "Error deleting record.";
    }
    $stmt->close();
    exit; // Stop further execution after deletion
}

// Handle update request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $update_id = $_POST['update_id'];
    $product_name = $_POST['product_name'] ?? '';
    $sku = $_POST['sku'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $cost_per_unit = $_POST['cost_per_unit'] ?? '';
    $supplier = $_POST['supplier'] ?? '';
    $reorder_level = $_POST['reorder_level'] ?? '';
    $warehouse_location = $_POST['warehouse_location'] ?? '';

    $update_sql = "UPDATE inventory SET 
        product_name = ?, 
        sku = ?, 
        quantity = ?, 
        cost_per_unit = ?, 
        supplier = ?, 
        reorder_level = ?, 
        warehouse_location = ? 
        WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param(
        "ssidsisi",
        $product_name,
        $sku,
        $quantity,
        $cost_per_unit,
        $supplier,
        $reorder_level,
        $warehouse_location,
        $update_id
    );

    if ($stmt->execute()) {
        echo "Record updated successfully!";
    } else {
        echo "Error updating record.";
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
    <title>SoleSuite - Inventory Management</title>
    <link rel="stylesheet" href="../css/form.css">
    <style>
        .clickable-row {
            cursor: pointer;
        }
        .clickable-row:hover {
            background-color: #f0f0f0;
        }
        .hidden {
            display: none;
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
                    <a href="form.php">
                        <button class="add-btn">ADD</button>
                    </a>
                </div>
                
            </header>

            <!-- Record List -->
            <section class="record-list">
                <h2>Inventory Records</h2>
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
                            <th>Product Name</th>
                            <th>SKU</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($result && $result->num_rows > 0) { 
                            while ($row = $result->fetch_assoc()) { 
                                echo '<tr class="clickable-row" 
                                        data-id="' . $row['id'] . '" 
                                        data-product_name="' . $row['product_name'] . '" 
                                        data-sku="' . $row['sku'] . '" 
                                        data-quantity="' . $row['quantity'] . '" 
                                        data-cost_per_unit="' . $row['cost_per_unit'] . '" 
                                        data-supplier="' . $row['supplier'] . '" 
                                        data-reorder_level="' . $row['reorder_level'] . '" 
                                        data-warehouse_location="' . $row['warehouse_location'] . '">';
                                echo '<td>' . $row['product_name'] . '</td>';
                                echo '<td>' . $row['sku'] . '</td>';
                                echo '<td>' . $row['quantity'] . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo "<tr><td colspan='3'>No records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>

            <!-- Form for Viewing/Updating Records -->
            <section class="form-container" id="update-form">
                <div class="form-header">
                    <h2>Update Inventory Record</h2>
                </div>
                <form id="update-form">
                    <label>Product Name</label>
                    <input type="text" id="product_name" name="product_name" value="" required>
                    
                    <label>SKU</label>
                    <input type="text" id="sku" name="sku" value="" required>
                    
                    <label>Quantity</label>
                    <input type="number" id="quantity" name="quantity" value="" required>
                    
                    <label>Cost per Unit</label>
                    <input type="number" step="0.01" id="cost_per_unit" name="cost_per_unit" value="" required>
                    
                    <label>Supplier</label>
                    <input type="text" id="supplier" name="supplier" value="" required>
                    
                    <label>Reorder Level</label>
                    <input type="number" id="reorder_level" name="reorder_level" value="" required>
                    
                    <label>Warehouse Location</label>
                    <input type="text" id="warehouse_location" name="warehouse_location" value="" required>
                    
                    <div class="form-buttons">
                        <button type="button" class="close-btn" onclick="closeForm()">CLOSE</button>
                        <button type="button" class="register-btn" onclick="updateRecord()">UPDATE</button>
                        <button type="button" class="register-btn" style="background-color: red;" onclick="deleteRecord()">DELETE</button>
                    </div>
                </form>
            </section>
        </main>
    </div>

    <script>
        // JavaScript for managing interactions in Inventory Management (IM.php)

        // Event listener for each inventory record row
        document.querySelectorAll('.clickable-row').forEach(row => {
            row.addEventListener('click', function () {
                // Remove selected class from previously selected rows
                document.querySelectorAll('.clickable-row').forEach(r => r.classList.remove('selected'));

                // Add selected class to the clicked row
                this.classList.add('selected');

                // Hide the inventory list (table)
                document.querySelector('.record-list').style.display = 'none';
                document.querySelector('.top-bar').style.display = 'none';

                // Show the form container for editing
                document.querySelector('.form-container').style.display = 'block';

                // Populate form with record data from the clicked row
                document.getElementById('product_name').value = this.dataset.product_name;
                document.getElementById('sku').value = this.dataset.sku;
                document.getElementById('quantity').value = this.dataset.quantity;
                document.getElementById('cost_per_unit').value = this.dataset.cost_per_unit;
                document.getElementById('supplier').value = this.dataset.supplier;
                document.getElementById('reorder_level').value = this.dataset.reorder_level;
                document.getElementById('warehouse_location').value = this.dataset.warehouse_location;

                // Store the `id` in the form container for later use
                document.getElementById('update-form').dataset.id = this.dataset.id;

                // Optionally, update the title or other elements based on row data
                document.getElementById('record-title').textContent = `Inventory ID: ${this.dataset.id} - ${this.dataset.product_name}`;
            });
        });

        // Close the form and return to the table view
        function closeForm() {
            document.querySelector('.form-container').style.display = 'none';
            document.querySelector('.record-list').style.display = 'block';
            document.querySelector('.top-bar').style.display = 'flex';
        }

        // Function to update an inventory record
        function updateRecord() {
            const formData = new FormData();
            const id = document.getElementById('update-form').dataset.id;

            // Add form data
            formData.append('update_id', id);
            formData.append('product_name', document.getElementById('product_name').value);
            formData.append('sku', document.getElementById('sku').value);
            formData.append('quantity', document.getElementById('quantity').value);
            formData.append('cost_per_unit', document.getElementById('cost_per_unit').value);
            formData.append('supplier', document.getElementById('supplier').value);
            formData.append('reorder_level', document.getElementById('reorder_level').value);
            formData.append('warehouse_location', document.getElementById('warehouse_location').value);

            // Send an AJAX POST request to update the record
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    alert(data); // Display success or error message
                    location.reload(); // Reload the page to refresh the table
                })
                .catch(error => console.error('Error:', error));
        }

        // Function to delete an inventory record
        function deleteRecord() {
            if (!confirm('Are you sure you want to delete this record?')) return;

            const formData = new FormData();
            const id = document.getElementById('update-form').dataset.id;

            // Add the `id` to the form data
            formData.append('delete_id', id);

            // Send an AJAX POST request to delete the record
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    alert(data); // Display success or error message
                    location.reload(); // Reload the page to refresh the table
                })
                .catch(error => console.error('Error:', error));
        }

    </script>
</body>
</html>

<?php
$conn->close();
?>
