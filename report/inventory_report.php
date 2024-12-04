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

// Fetch inventory data for the chart
$sql = "SELECT product_name, quantity, reorder_level FROM inventory";
$result = $conn->query($sql);

$products = [];
$quantities = [];
$reorderLevels = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row['product_name'];
        $quantities[] = $row['quantity'];
        $reorderLevels[] = $row['reorder_level'];
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
                <h1>Inventory Reports</h1>
            </header>
            <section class="chart-container">
                <h2>Product Quantities</h2>
                <canvas id="quantityChart"></canvas>
            </section>

            <section class="chart-container">
                <h2>Reorder Levels vs Quantities</h2>
                <canvas id="reorderChart"></canvas>
            </section>
        </main>
    </div>

    <script>
        // Data for Product Quantities Chart
        const productNames = <?php echo json_encode($products); ?>;
        const productQuantities = <?php echo json_encode($quantities); ?>;
        const reorderLevels = <?php echo json_encode($reorderLevels); ?>;

        // Quantity Chart
        const quantityCtx = document.getElementById('quantityChart').getContext('2d');
        new Chart(quantityCtx, {
            type: 'bar',
            data: {
                labels: productNames,
                datasets: [{
                    label: 'Quantity in Stock',
                    data: productQuantities,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Reorder Levels vs Quantities Chart
        const reorderCtx = document.getElementById('reorderChart').getContext('2d');
        new Chart(reorderCtx, {
            type: 'line',
            data: {
                labels: productNames,
                datasets: [
                    {
                        label: 'Quantity in Stock',
                        data: productQuantities,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 2,
                        tension: 0.1
                    },
                    {
                        label: 'Reorder Level',
                        data: reorderLevels,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderWidth: 2,
                        tension: 0.1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
