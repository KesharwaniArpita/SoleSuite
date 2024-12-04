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

// Fetch Revenue Analysis by Product
$sql = "SELECT p.name AS product_name, SUM(od.quantity * od.unit_price) AS total_revenue
        FROM order_details od
        JOIN products p ON od.product_id = p.id
        GROUP BY p.name
        ORDER BY total_revenue DESC";
$result = $conn->query($sql);

$productNames = [];
$productRevenue = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productNames[] = $row['product_name'];
        $productRevenue[] = $row['total_revenue'];
    }
}

// Fetch Top-Selling Products
$sql = "SELECT p.name AS product_name, SUM(od.quantity) AS total_sold
        FROM order_details od
        JOIN products p ON od.product_id = p.id
        GROUP BY p.name
        ORDER BY total_sold DESC";
$result = $conn->query($sql);

$topSellingProductNames = [];
$topSellingQuantities = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $topSellingProductNames[] = $row['product_name'];
        $topSellingQuantities[] = $row['total_sold'];
    }
}

// Fetch Monthly Sales Trends
$sql = "SELECT DATE_FORMAT(o.order_date, '%Y-%m') AS month, SUM(od.quantity * od.unit_price) AS monthly_revenue
        FROM orders o
        JOIN order_details od ON o.id = od.order_id
        GROUP BY month
        ORDER BY month";
$result = $conn->query($sql);

$months = [];
$monthlyRevenue = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $months[] = $row['month'];
        $monthlyRevenue[] = $row['monthly_revenue'];
    }
}

// Fetch Customer Insights - Total Spend
$sql = "SELECT c.name AS customer_name, SUM(od.quantity * od.unit_price) AS total_spent
        FROM orders o
        JOIN order_details od ON o.id = od.order_id
        JOIN customers c ON o.customer_id = c.id
        GROUP BY c.name
        ORDER BY total_spent DESC";
$result = $conn->query($sql);

$customerNames = [];
$customerSpend = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $customerNames[] = $row['customer_name'];
        $customerSpend[] = $row['total_spent'];
    }
}

// Fetch Sales Status Breakdown
$sql = "SELECT o.status, SUM(od.quantity * od.unit_price) AS total_sales
        FROM orders o
        JOIN order_details od ON o.id = od.order_id
        GROUP BY o.status";
$result = $conn->query($sql);

$salesStatus = [];
$salesAmount = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $salesStatus[] = $row['status'];
        $salesAmount[] = $row['total_sales'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="../css/form.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> <!-- FontAwesome icons -->
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
                    <h1>Sales Reports</h1>
                </header>
            
                    <!-- Revenue Analysis by Product -->
                    <section class="chart-container" style="width: 500px; margin: 0 auto;">
                        <h2>Revenue Analysis by Product</h2>
                        <canvas id="revenueChart" style="max-width: 100%; height: 500px;"></canvas>
                    </section>

                    <!-- Top-Selling Products -->
                    <section class="chart-container" style="width: 500px; margin: 0 auto;">
                        <h2>Top-Selling Products</h2>
                        <canvas id="topSellingChart" style="max-width: 100%; height: 500px;"></canvas>
                    </section>

                    <!-- Monthly Sales Trends -->
                    <section class="chart-container" style="width: 500px; margin: 0 auto;">
                        <h2>Monthly Sales Trends</h2>
                        <canvas id="monthlySalesChart" style="max-width: 100%; height: 500px;"></canvas>
                    </section>

                    <!-- Customer Insights - Total Spend -->
                    <section class="chart-container" style="width: 500px; margin: 0 auto;">
                        <h2>Customer Insights - Total Spend</h2>
                        <canvas id="customerChart" style="max-width: 100%; height: 500px;"></canvas>
                    </section>

                    <!-- Sales Status Breakdown -->
                    <section class="chart-container" style="width: 500px; margin: 0 auto;">
                        <h2>Sales Status Breakdown</h2>
                        <canvas id="salesStatusChart" style="max-width: 100%; height: 500px;"></canvas>
                    </section>


            </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
    <script>
        // Revenue Analysis by Product
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($productNames); ?>,
                datasets: [{
                    label: 'Total Revenue',
                    data: <?php echo json_encode($productRevenue); ?>,
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

        // Top-Selling Products
        const topSellingCtx = document.getElementById('topSellingChart').getContext('2d');
        new Chart(topSellingCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($topSellingProductNames); ?>,
                datasets: [{
                    label: 'Total Quantity Sold',
                    data: <?php echo json_encode($topSellingQuantities); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
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

        // Monthly Sales Trends
        const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
        new Chart(monthlySalesCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Monthly Revenue',
                    data: <?php echo json_encode($monthlyRevenue); ?>,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 2,
                    tension: 0.1
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

        // Customer Insights - Total Spend
        const customerCtx = document.getElementById('customerChart').getContext('2d');
        new Chart(customerCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($customerNames); ?>,
                datasets: [{
                    label: 'Total Spend',
                    data: <?php echo json_encode($customerSpend); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
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

        // Sales Status Breakdown
        const salesStatusCtx = document.getElementById('salesStatusChart').getContext('2d');
        new Chart(salesStatusCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($salesStatus); ?>,
                datasets: [{
                    label: 'Sales Status',
                    data: <?php echo json_encode($salesAmount); ?>,
                    backgroundColor: ['rgba(54, 162, 235, 0.6)', 'rgba(255, 99, 132, 0.6)', 'rgba(75, 192, 192, 0.6)']
                }]
            }
        });
    </script>

</body>
</html>
