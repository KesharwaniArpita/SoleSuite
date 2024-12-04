<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "solesuite";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Supplier Contribution by Product
$sql1 = "SELECT supplier_name, SUM(quantity_ordered) AS total_quantity FROM supply GROUP BY supplier_name";
$result1 = $conn->query($sql1);
$suppliers = [];
$contributions = [];

if ($result1 && $result1->num_rows > 0) {
    while ($row = $result1->fetch_assoc()) {
        $suppliers[] = $row['supplier_name'];
        $contributions[] = $row['total_quantity'];
    }
}

// Order Trends Over Time
$sql2 = "SELECT DATE_FORMAT(order_date, '%Y-%m') AS order_month, COUNT(order_id) AS total_orders FROM supply GROUP BY order_month";
$result2 = $conn->query($sql2);
$orderMonths = [];
$orderCounts = [];

if ($result2 && $result2->num_rows > 0) {
    while ($row = $result2->fetch_assoc()) {
        $orderMonths[] = $row['order_month'];
        $orderCounts[] = $row['total_orders'];
    }
}

// On-Time vs Late Deliveries
$sql3 = "SELECT 
            CASE 
                WHEN expected_delivery_date >= CURDATE() THEN 'On Time' 
                ELSE 'Late' 
            END AS delivery_status,
            COUNT(order_id) AS total_orders 
         FROM supply 
         GROUP BY delivery_status";
$result3 = $conn->query($sql3);
$deliveryStatuses = [];
$deliveryCounts = [];

if ($result3 && $result3->num_rows > 0) {
    while ($row = $result3->fetch_assoc()) {
        $deliveryStatuses[] = $row['delivery_status'];
        $deliveryCounts[] = $row['total_orders'];
    }
}

// Status Breakdown
$sql4 = "SELECT status, COUNT(order_id) AS total_orders FROM supply GROUP BY status";
$result4 = $conn->query($sql4);
$orderStatuses = [];
$statusCounts = [];

if ($result4 && $result4->num_rows > 0) {
    while ($row = $result4->fetch_assoc()) {
        $orderStatuses[] = $row['status'];
        $statusCounts[] = $row['total_orders'];
    }
}

// Product Popularity in Orders
$sql5 = "SELECT product_name, SUM(quantity_ordered) AS total_quantity FROM supply GROUP BY product_name";
$result5 = $conn->query($sql5);
$productNames = [];
$productQuantities = [];

if ($result5 && $result5->num_rows > 0) {
    while ($row = $result5->fetch_assoc()) {
        $productNames[] = $row['product_name'];
        $productQuantities[] = $row['total_quantity'];
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>supplier_name Reports</title>
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
                    ["supplier Manage", "../supplier/SCM.php", "../img/i1.png"],
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
                <h1>Supply Chain Reports</h1>
            </header>

            <!-- Supplier Contribution by Product -->
            <section class="chart-container" style="width: 500px; margin: 0 auto;">
                <h2>Supplier Contribution by Product</h2>
                <canvas id="supplierContributionChart" style="max-width: 100%; height: 400px;"></canvas>
            </section>

            <!-- Order Trends Over Time -->
            <section class="chart-container" style="width: 500px; margin: 0 auto;">
                <h2>Order Trends Over Time</h2>
                <canvas id="orderTrendsChart" style="max-width: 100%; height: 400px;"></canvas>
            </section>

            <!-- On-Time vs Late Deliveries -->
            <section class="chart-container" style="width: 500px; margin: 0 auto;">
                <h2>On-Time vs Late Deliveries</h2>
                <canvas id="deliveryStatusChart" style="max-width: 100%; height: 400px;"></canvas>
            </section>

            <!-- Status Breakdown -->
            <section class="chart-container" style="width: 500px; margin: 0 auto;">
                <h2>Status Breakdown</h2>
                <canvas id="statusBreakdownChart" style="max-width: 100%; height: 400px;"></canvas>
            </section>

            <!-- Product Popularity in Orders -->
            <section class="chart-container" style="width: 500px; margin: 0 auto;">
                <h2>Product Popularity in Orders</h2>
                <canvas id="productPopularityChart" style="max-width: 100%; height: 400px;"></canvas>
            </section>


        </main>
    </div>

    <script>
        // Supplier Contribution Chart
        const suppliers = <?php echo json_encode($suppliers); ?>;
        const contributions = <?php echo json_encode($contributions); ?>;

        new Chart(document.getElementById('supplierContributionChart'), {
            type: 'bar',
            data: {
                labels: suppliers,
                datasets: [{
                    label: 'Total Quantity Supplied',
                    data: contributions,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        // Order Trends Over Time
        const orderMonths = <?php echo json_encode($orderMonths); ?>;
        const orderCounts = <?php echo json_encode($orderCounts); ?>;

        new Chart(document.getElementById('orderTrendsChart'), {
            type: 'line',
            data: {
                labels: orderMonths,
                datasets: [{
                    label: 'Number of Orders',
                    data: orderCounts,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 2,
                    tension: 0.3
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });


        // On-Time vs Late Deliveries
        const deliveryStatuses = <?php echo json_encode($deliveryStatuses); ?>;
        const deliveryCounts = <?php echo json_encode($deliveryCounts); ?>;

        new Chart(document.getElementById('deliveryStatusChart'), {
            type: 'doughnut',
            data: {
                labels: deliveryStatuses,
                datasets: [{
                    data: deliveryCounts,
                    backgroundColor: ['#4caf50', '#f44336'],
                    borderColor: ['#4caf50', '#f44336'],
                    borderWidth: 1
                }]
            },
            options: { responsive: true }
        });
        // Status Breakdown
        const orderStatuses = <?php echo json_encode($orderStatuses); ?>;
        const statusCounts = <?php echo json_encode($statusCounts); ?>;

        new Chart(document.getElementById('statusBreakdownChart'), {
            type: 'bar',
            data: {
                labels: orderStatuses,
                datasets: [{
                    label: 'Order Status Count',
                    data: statusCounts,
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        // Product Popularity in Orders
        const productNames = <?php echo json_encode($productNames); ?>;
        const productQuantities = <?php echo json_encode($productQuantities); ?>;

        new Chart(document.getElementById('productPopularityChart'), {
            type: 'bar',
            data: {
                labels: productNames,
                datasets: [{
                    label: 'Quantity Ordered',
                    data: productQuantities,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
    </script>

</body>
</html>
