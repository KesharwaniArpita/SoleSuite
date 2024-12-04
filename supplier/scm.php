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

// Fetch supply chain records from the database
$sql = "SELECT order_id, product_name, supplier_name, quantity_ordered, order_date, expected_delivery_date, status, tracking_number FROM supply";
$result = $conn->query($sql);

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Prepare delete query
    $delete_sql = "DELETE FROM supply WHERE order_id = ?";
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
    $supplier_name = $_POST['supplier_name'] ?? '';
    $quantity_ordered = $_POST['quantity_ordered'] ?? '';
    $order_date = $_POST['order_date'] ?? '';
    $expected_delivery_date = $_POST['expected_delivery_date'] ?? '';
    $status = $_POST['status'] ?? '';
    $tracking_number = $_POST['tracking_number'] ?? '';

    $update_sql = "UPDATE supply SET 
        product_name = ?, 
        supplier_name = ?, 
        quantity_ordered = ?, 
        order_date = ?, 
        expected_delivery_date = ?, 
        status = ?, 
        tracking_number = ? 
        WHERE order_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param(
        "ssissssi",
        $product_name,
        $supplier_name,
        $quantity_ordered,
        $order_date,
        $expected_delivery_date,
        $status,
        $tracking_number,
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
    <title>SoleSuite - Supply Chain Management</title>
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
            <section class="record-list" >
                <h2 style>Supply Chain Records</h2>
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
                            <th>Supplier Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($result && $result->num_rows > 0) { 
                            while ($row = $result->fetch_assoc()) { 
                                echo '<tr class="clickable-row" 
                                        data-order_id="' . $row['order_id'] . '" 
                                        data-product_name="' . $row['product_name'] . '" 
                                        data-supplier_name="' . $row['supplier_name'] . '" 
                                        data-quantity_ordered="' . $row['quantity_ordered'] . '" 
                                        data-order_date="' . $row['order_date'] . '" 
                                        data-expected_delivery_date="' . $row['expected_delivery_date'] . '" 
                                        data-status="' . $row['status'] . '" 
                                        data-tracking_number="' . $row['tracking_number'] . '">';
                                echo '<td>' . $row['product_name'] . '</td>';
                                echo '<td>' . $row['supplier_name'] . '</td>';
                                echo '<td>' . $row['status'] . '</td>';
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
                    <h2 id="record-title">Record Information</h2>
                </div>
                <form id="update-form">
                    <label>Product Name</label>
                    <input type="text" id="product_name" name="product_name" value="" required>
                    
                    <label>Supplier Name</label>
                    <input type="text" id="supplier_name" name="supplier_name" value="" required>
                    
                    <label>Quantity Ordered</label>
                    <input type="number" id="quantity_ordered" name="quantity_ordered" value="" required>
                    
                    <label>Order Date</label>
                    <input type="date" id="order_date" name="order_date" value="" required>
                    
                    <label>Expected Delivery Date</label>
                    <input type="date" id="expected_delivery_date" name="expected_delivery_date" value="" required>
                    
                    <label>Status</label>
                    <input type="text" id="status" name="status" value="" required>
                    
                    <label>Tracking Number</label>
                    <input type="text" id="tracking_number" name="tracking_number" value="" required>
                    
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
        // JavaScript for managing interactions

        document.querySelectorAll('.clickable-row').forEach(row => {
            row.addEventListener('click', function () {
                // Remove selected class from any previously selected row
                document.querySelectorAll('.clickable-row').forEach(r => r.classList.remove('selected'));

                // Add selected class to the clicked row
                this.classList.add('selected');

                // Hide the product list (table)
                document.querySelector('.record-list').style.display = 'none';
                document.querySelector('.top-bar').style.display = 'none';

                // Expand the clicked row (optional: you can toggle a class for more content)
                this.classList.toggle('expanded');  // Add an expanded class to show additional details (you can style it via CSS)

                // Show the form container for editing
                document.querySelector('.form-container').style.display = 'block';

                // Populate form with record data from the clicked row
                document.getElementById('product_name').value = this.dataset.product_name;
                document.getElementById('supplier_name').value = this.dataset.supplier_name;
                document.getElementById('quantity_ordered').value = this.dataset.quantity_ordered;
                document.getElementById('order_date').value = this.dataset.order_date;
                document.getElementById('expected_delivery_date').value = this.dataset.expected_delivery_date;
                document.getElementById('status').value = this.dataset.status;
                document.getElementById('tracking_number').value = this.dataset.tracking_number;

                // Store the `order_id` in the form container for later use
                document.getElementById('update-form').dataset.order_id = this.dataset.order_id;

                // Optionally, update the title or other elements based on row data
                document.getElementById('order-title').textContent = `Order ID: ${this.dataset.order_id} - ${this.dataset.product_name}`;
            });
        });



        // Close the form
        function closeForm() {
            document.querySelector('.form-container').style.display = 'none';
            document.querySelector('.record-list').style.display = 'block';
            document.querySelector('.top-bar').style.display = 'flex';
        }


        // Function to update a record
        function updateRecord() {
            const formData = new FormData();
            const order_id = document.getElementById('update-form').dataset.order_id;

            // Add form data
            formData.append('update_id', order_id);
            formData.append('product_name', document.getElementById('product_name').value);
            formData.append('supplier_name', document.getElementById('supplier_name').value);
            formData.append('quantity_ordered', document.getElementById('quantity_ordered').value);
            formData.append('order_date', document.getElementById('order_date').value);
            formData.append('expected_delivery_date', document.getElementById('expected_delivery_date').value);
            formData.append('status', document.getElementById('status').value);
            formData.append('tracking_number', document.getElementById('tracking_number').value);

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

        // Function to delete a record
        function deleteRecord() {
            if (!confirm('Are you sure you want to delete this record?')) return;

            const formData = new FormData();
            const order_id = document.getElementById('update-form').dataset.order_id;

            // Add the `order_id` to the form data
            formData.append('delete_id', order_id);

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
