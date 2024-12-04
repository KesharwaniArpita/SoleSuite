<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoleSuite - Registration Form</title>
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
            <header class="top-bar">
                <!-- <div class="user-info">
                    <button class="add-btn">ADD</button>
                </div>
                <div class="search-bar">
                    <input type="text" placeholder="Search">
                    <button>Search</button>
                </div> -->
            </header>
            <section class="registration-form">
                <div class="form-header">
                    <h2 style="text-align: center;">REGISTRATION CUSTOMER FORM</h2>
                </div>

                <form id="registrationForm" method="POST">
                    <label>Name</label>
                    <input type="text" name="name" placeholder="Enter name" required>
                    
                    <label>User Role</label>
                    <select name="user_role" required>
                        <option value="" disabled selected>Select role</option>
                        <option value="Admin">Admin</option>
                        <option value="User">User</option>
                    </select>
                    
                    <label>Mobile</label>
                    <input type="text" name="mobile" placeholder="Enter mobile" required>
                    
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Enter email" required>
                    
                    <label>Address</label>
                    <input type="text" name="address" placeholder="Enter address" required>
                    
                    <label>Date of Birth</label>
                    <input type="date" name="dob" required>
                    
                    <label>Country</label>
                    <select name="country" required>
                        <option value="" disabled selected>Select country</option>
                        <option value="USA">USA</option>
                        <option value="India">India</option>
                        <option value="Canada">Canada</option>
                    </select>
                    
                    <label>City</label>
                    <input type="text" name="city" placeholder="Enter city" required>
                    
                    <div class="form-buttons">
                        <button type="button" class="close-btn" onclick="closeForm()">CLOSE</button>
                        <button type="submit" class="register-btn">REGISTER</button>
                    </div>
                </form>                
            </section>
        </main>
    </div>
    <script>
        // JavaScript to handle form submission
        document.getElementById('registrationForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(this);

            fetch('', { // Same page for PHP handling
                method: 'POST',
                body: formData,
            })
            .then(response => response.text())
            .then(data => {
                alert("Registration Successful"); // Show success/error message
                this.reset(); // Reset the form
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the data.');
            });
        });

        // Function to close the form and redirect to CRM.php
        function closeForm() {
            window.location.href = "CRM.php"; // Redirect to CRM.php when Close button is pressed
        }
    </script>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = ""; // Update if necessary
        $dbname = "solesuite"; // Ensure this database exists

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Retrieve form data
        $name = $_POST['name'];
        $user_role = $_POST['user_role'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $dob = $_POST['dob'];
        $country = $_POST['country'];
        $city = $_POST['city'];

        // SQL query to insert data
        $sql = "INSERT INTO customers (name, user_role, mobile, email, address, dob, country, city)
                VALUES ('$name', '$user_role', '$mobile', '$email', '$address', '$dob', '$country', '$city')";

        if ($conn->query($sql) === TRUE) {
            echo "Customer registered successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
    ?>
</body>
</html>
