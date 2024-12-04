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

// Fetch user records from the database
$sql = "SELECT id, full_name, username, email, role, phone_number, status FROM users";
$result = $conn->query($sql);

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Prepare delete query
    $delete_sql = "DELETE FROM users WHERE id = ?";
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
    $full_name = $_POST['full_name'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $role = $_POST['role'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $status = $_POST['status'] ?? '';

    // Prepare SQL query for updating the user record
    $update_sql = "UPDATE users SET 
        full_name = ?, 
        username = ?, 
        email = ?, 
        role = ?, 
        phone_number = ?, 
        status = ? 
        WHERE id = ?";
    
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param(
        "ssssssi",
        $full_name,
        $username,
        $email,
        $role,
        $phone_number,
        $status,
        $update_id
    );

    if ($stmt->execute()) {
        echo "Record updated successfully!";
    } else {
        echo "Error updating record.";
    }
    $stmt->close();
    exit; // Stop further execution after the update
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoleSuite - User Management</title>
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
            <section class="record-list" align="center">
                <h2>User Records</h2>
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
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($result && $result->num_rows > 0) { 
                            while ($row = $result->fetch_assoc()) { 
                                echo '<tr class="clickable-row" 
                                        data-id="' . $row['id'] . '" 
                                        data-full_name="' . $row['full_name'] . '" 
                                        data-username="' . $row['username'] . '" 
                                        data-email="' . $row['email'] . '" 
                                        data-role="' . $row['role'] . '" 
                                        data-phone_number="' . $row['phone_number'] . '" 
                                        data-status="' . $row['status'] . '">';
                                echo '<td>' . $row['full_name'] . '</td>';
                                echo '<td>' . $row['username'] . '</td>';
                                echo '<td>' . $row['email'] . '</td>';
                                echo '<td>' . $row['role'] . '</td>';
                                echo '<td>' . $row['status'] . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo "<tr><td colspan='5'>No records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>

            <!-- Form for Viewing/Updating User -->
            <section class="form-container" id="update-form">
                <div class="form-header">
                    <h2 id="record-title">User Information</h2>
                </div>
                <form id="update-form">
                    <label>Full Name</label>
                    <input type="text" id="full_name" name="full_name" value="" required>
                    
                    <label>Username</label>
                    <input type="text" id="username" name="username" value="" required>
                    
                    <label>Email</label>
                    <input type="email" id="email" name="email" value="" required>
                    
                    <label>Role</label>
                    <select id="role" name="role" required>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                    
                    <label>Phone Number</label>
                    <input type="text" id="phone_number" name="phone_number" value="" required>
                    
                    <label>Status</label>
                    <select id="status" name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    
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
                document.querySelectorAll('.clickable-row').forEach(r => r.classList.remove('selected'));
                this.classList.add('selected');
                document.querySelector('.record-list').style.display = 'none';
                document.querySelector('.form-container').style.display = 'block';

                document.getElementById('full_name').value = this.dataset.full_name;
                document.getElementById('username').value = this.dataset.username;
                document.getElementById('email').value = this.dataset.email;
                document.getElementById('role').value = this.dataset.role;
                document.getElementById('phone_number').value = this.dataset.phone_number;
                document.getElementById('status').value = this.dataset.status;
                document.getElementById('update-form').dataset.id = this.dataset.id;
            });
        });

        function closeForm() {
            document.querySelector('.record-list').style.display = 'block';
            document.querySelector('.form-container').style.display = 'none';
        }

        // Function to update the record
        function updateRecord() {
            const formData = new FormData();
            const id = document.getElementById('update-form').dataset.id; // Get the record id

            // Add form data
            formData.append('update_id', id);
            formData.append('full_name', document.getElementById('full_name').value);
            formData.append('username', document.getElementById('username').value);
            formData.append('email', document.getElementById('email').value);
            formData.append('role', document.getElementById('role').value);
            formData.append('phone_number', document.getElementById('phone_number').value);
            formData.append('status', document.getElementById('status').value);

            // Send an AJAX POST request to the current page
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    alert(data); // Display success or error message
                    location.reload(); // Reload the page to refresh the user records
                })
                .catch(error => console.error('Error:', error)); // Log any errors
        }

        // Event listener for each user record row
        document.querySelectorAll('.clickable-row').forEach(row => {
            row.addEventListener('click', function () {
                // Remove selected class from previously selected rows
                document.querySelectorAll('.clickable-row').forEach(r => r.classList.remove('selected'));

                // Add selected class to the clicked row
                this.classList.add('selected');

                // Hide the user list (table) and show the form for editing
                document.querySelector('.record-list').style.display = 'none';
                document.querySelector('.form-container').style.display = 'block';

                // Populate the form with the clicked row data
                document.getElementById('full_name').value = this.dataset.full_name;
                document.getElementById('username').value = this.dataset.username;
                document.getElementById('email').value = this.dataset.email;
                document.getElementById('role').value = this.dataset.role;
                document.getElementById('phone_number').value = this.dataset.phone_number;
                document.getElementById('status').value = this.dataset.status;

                // Store the `id` in the form container for later use
                document.getElementById('update-form').dataset.id = this.dataset.id;
            });
        });


        function deleteRecord() {
            const form = document.getElementById('update-form');
            const id = form.dataset.id;

            if (confirm('Are you sure you want to delete this record?')) {
                const data = new FormData();
                data.append('delete_id', id);

                fetch('UM.php', {
                    method: 'POST',
                    body: data,
                }).then(response => response.text())
                  .then(result => {
                      alert(result);
                      window.location.reload(); // Reload the page to reflect changes
                  });
            }
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
