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

// Fetch customers from the database
$sql = "SELECT id, name, mobile, email, address, dob, country, city, user_role FROM customers";
$result = $conn->query($sql);

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Prepare delete query
    $delete_sql = "DELETE FROM customers WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "Customer deleted successfully!";
    } else {
        echo "Error deleting customer.";
    }
    $stmt->close();
    exit; // Stop further execution after deletion
}

// Handle update request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $update_id = $_POST['update_id'];
    $name = $_POST['name'] ?? '';
    $user_role = $_POST['user_role'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $country = $_POST['country'] ?? '';
    $city = $_POST['city'] ?? '';

    error_log("Updating ID: $update_id, Name: $name");

    $update_sql = "UPDATE customers SET name = ?, user_role = ?, mobile = ?, email = ?, address = ?, dob = ?, country = ?, city = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssssssi", $name, $user_role, $mobile, $email, $address, $dob, $country, $city, $update_id);

    if ($stmt->execute()) {
        echo "Customer updated successfully!";
    } else {
        error_log("SQL Error: " . $stmt->error);
        echo "Error updating customer.";
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
    <title>SoleSuite - Customer Management</title>
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
                <!-- <div class="search-bar">
                    <input type="text" placeholder="Search">
                    <button>Search</button>
                </div> -->
            </header>

            <!-- Customer List -->
            <section class="customer-list" align="center">
                <h2>Customer List</h2>
                    <style>
        /* General Table Styling */
        .customer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff; /* Table background */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Adds subtle shadow */
            border-radius: 8px; /* Rounded corners */
        }

        .customer-table th, .customer-table td {
            padding: 10px;
            text-align: left;
            font-size: 1rem;
            color: #555;
            border-bottom: 1px solid #E4E8E5;
        }

        .customer-table th {
            background-color: #E4E8E5;
            font-weight: bold;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .customer-table tr:hover {
            background-color: #f7f9fc; /* Light hover effect */
            cursor: pointer;
        }

        .customer-table tr.clickable-row:hover {
            background-color: #91BDE5; /* Highlight row on hover */
            color: white; /* Text color change */
        }

        .customer-table td {
            font-size: 1rem;
            color: #555;
        }

        /* Optional: Styling for "No customers found" message */
        .customer-table tr td[colspan="2"] {
            text-align: center;
            color: #999;
            font-size: 1.2rem;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .customer-table th, .customer-table td {
                font-size: 0.9rem;
                padding: 8px;
            }
        }

        /* Heading styling */
        .customer-list h2 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
        }
    </style>
                <table class="customer-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact</th>
                        </tr>
                   </thead>
                    <tbody>
                       <?php 
                            if ($result && $result->num_rows > 0) { 
                                while ($row = $result->fetch_assoc()) { 
                                    echo '<tr class="clickable-row" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '" data-mobile="' . $row['mobile'] . '" data-email="' . $row['email'] . '" data-address="' . $row['address'] . '" data-dob="' . $row['dob'] . '" data-country="' . $row['country'] . '" data-city="' . $row['city'] . '" data-user_role="' . $row['user_role'] . '">';
                                    echo '<td>' . $row['name'] . '</td>';
                                    echo '<td>' . $row['mobile'] . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo "<tr><td colspan='2'>No customers found</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>

            </section>

            <!-- Customer Details Form -->
            <section class="form-container" id="customer-form">
                <div class="form-header">
                    <h2 id="customer-title">Customer Information</h2>
                </div>
                <form id="update-form">
                    <label>Name</label>
                    <input type="text" id="name" name="name" value="" required>
                    
                    <label>User Role</label>
                    <input type="text" id="user_role" name="user_role" value="" required>
                    
                    <label>Mobile</label>
                    <input type="text" id="mobile" name="mobile" value="" required>
                    
                    <label>Email</label>
                    <input type="text" id="email" name="email" value="" required>
                    
                    <label>Address</label>
                    <input type="text" id="address" name="address" value="" required>
                    
                    <label>Date of Birth</label>
                    <input type="text" id="dob" name="dob" value="" required>
                    
                    <label>Country</label>
                    <input type="text" id="country" name="country" value="" required>
                    
                    <label>City</label>
                    <input type="text" id="city" name="city" value="" required>
                    
                    <div class="form-buttons">
                        <button type="button" class="close-btn" onclick="closeForm()">CLOSE</button>
                        <button type="button" class="register-btn" onclick="updateCustomer()">UPDATE</button>
                        <button type="button" class="register-btn" style="background-color: red;" onclick="deleteCustomer()">DELETE</button>
                    </div>
                </form>
            </section>
        </main>
    </div>

    <script>
        // JavaScript function to show the form and hide the customer list, add/search bar
        document.querySelectorAll('.clickable-row').forEach(row => {
            row.addEventListener('click', function() {
                // Remove selected class from any previously selected row
                document.querySelectorAll('.clickable-row').forEach(r => r.classList.remove('selected'));
                
                // Add selected class to the clicked row
                this.classList.add('selected');

                // Hide customer list and add/search bar
                document.querySelector('.customer-list').style.display = 'none';
                document.querySelector('.top-bar').style.display = 'none';

                // Show form container
                document.querySelector('.form-container').style.display = 'block';

                // Populate form with clicked row data
                document.getElementById('name').value = this.getAttribute('data-name');
                document.getElementById('user_role').value = this.getAttribute('data-user_role');
                document.getElementById('mobile').value = this.getAttribute('data-mobile');
                document.getElementById('email').value = this.getAttribute('data-email');
                document.getElementById('address').value = this.getAttribute('data-address');
                document.getElementById('dob').value = this.getAttribute('data-dob');
                document.getElementById('country').value = this.getAttribute('data-country');
                document.getElementById('city').value = this.getAttribute('data-city');
                document.getElementById('customer-title').textContent = this.getAttribute('data-user_role') + ': ' + this.getAttribute('data-name');
            });
        });


        // JavaScript function to close the form and show the list again
        function closeForm() {
            document.querySelector('.form-container').style.display = 'none';
            document.querySelector('.customer-list').style.display = 'block';
            document.querySelector('.top-bar').style.display = 'flex';
        }

        // Function to delete the customer
        function deleteCustomer() {
            var customerId = document.querySelector('.clickable-row').getAttribute('data-id');
            
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "CRM.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert("Customer deleted successfully!");
                    location.reload();  // Reload page after deletion
                } else {
                    alert("Error deleting customer.");
                }
            };
            xhr.send("delete_id=" + customerId);
        }

        // Function to update the customer
        function updateCustomer() {
            var selectedRow = document.querySelector('.clickable-row.selected');
            if (!selectedRow) {
                alert("No customer selected!");
                return;
            }

            var customerId = selectedRow.getAttribute('data-id');

            var params = new URLSearchParams();
            params.append('update_id', customerId);
            params.append('name', document.getElementById('name').value);
            params.append('user_role', document.getElementById('user_role').value);
            params.append('mobile', document.getElementById('mobile').value);
            params.append('email', document.getElementById('email').value);
            params.append('address', document.getElementById('address').value);
            params.append('dob', document.getElementById('dob').value);
            params.append('country', document.getElementById('country').value);
            params.append('city', document.getElementById('city').value);

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "CRM.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert("Customer updated successfully!");
                    location.reload(); // Reload page after update
                } else {
                    alert("Error updating customer.");
                    console.error('Server Error:', xhr.responseText);
                }
            };
            xhr.send(params.toString());
        }


    </script>
</body>
</html>

<?php
$conn->close();
?>
