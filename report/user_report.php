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

// Fetch data for User Role Distribution
$roleSql = "SELECT role, COUNT(*) AS count FROM users GROUP BY role";
$roleResult = $conn->query($roleSql);

$roles = [];
$roleCounts = [];

if ($roleResult && $roleResult->num_rows > 0) {
    while ($row = $roleResult->fetch_assoc()) {
        $roles[] = $row['role'];
        $roleCounts[] = $row['count'];
    }
}

// Fetch data for Account Status Breakdown
$statusSql = "SELECT status, COUNT(*) AS count FROM users GROUP BY status";
$statusResult = $conn->query($statusSql);

$statuses = [];
$statusCounts = [];

if ($statusResult && $statusResult->num_rows > 0) {
    while ($row = $statusResult->fetch_assoc()) {
        $statuses[] = $row['status'];
        $statusCounts[] = $row['count'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <link rel="stylesheet" href="../css/form.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
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
                <?php
                $menuItems = [
                    ["Customer Manage", "../customer/CRM.php", "../img/i1.png"],
                    ["Product Manage", "../product/PM.php", "../img/i2.png"],
                    ["Supplier Manage", "../supplier/SCM.php", "../img/i1.png"],
                    ["Inventory Manage", "../inventory/IM.php", "../img/i1.png"],
                    
                    ["Report", "../report/report.php", "../img/i3.png"],
                    ["Logout", "../Logout.php", "../img/i4.png"]
                ];

                foreach ($menuItems as $item) {
                    echo "
                    <li>
                        <a href='{$item[1]}'>
                            <img src='{$item[2]}' alt='{$item[0]} Icon'>
                            <span>{$item[0]}</span>
                        </a>
                    </li>";
                }
                ?>
            </ul>
        </aside>
        
        <main>
            <header>
                <h1>User Reports</h1>
            </header>

            <!-- User Role Distribution -->
            <section class="chart-container" style="width: 500px; margin: 0 auto;">
                <h2>User Role Distribution</h2>
                <canvas id="userRoleChart" style="max-width: 100%; height: 500px;"></canvas>
            </section>

            <!-- Account Status Breakdown -->
            <section class="chart-container" style="width: 500px; margin: 0 auto;">
                <h2>Account Status Breakdown</h2>
                <canvas id="accountStatusChart" style="max-width: 100%; height: 500px;"></canvas>
            </section>
        </main>


    <script>
        // Data for User Role Distribution
        const roles = <?php echo json_encode($roles); ?>;
        const roleCounts = <?php echo json_encode($roleCounts); ?>;

        const roleCtx = document.getElementById('userRoleChart').getContext('2d');
        new Chart(roleCtx, {
            type: 'pie',
            data: {
                labels: roles,
                datasets: [{
                    data: roleCounts,
                    backgroundColor: ['#4caf50', '#ff9800', '#2196f3', '#e91e63'], // Add more colors if needed
                }]
            },
            options: {
                responsive: true,
            }
        });

        // Data for Account Status Breakdown
        const statuses = <?php echo json_encode($statuses); ?>;
        const statusCounts = <?php echo json_encode($statusCounts); ?>;

        const statusCtx = document.getElementById('accountStatusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: statuses,
                datasets: [{
                    data: statusCounts,
                    backgroundColor: ['#4caf50', '#e91e63'], // Green for Active, Red for Inactive
                }]
            },
            options: {
                responsive: true,
            }
        });
    </script>
</body>
</html>
