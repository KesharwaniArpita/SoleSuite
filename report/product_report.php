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

// Price Distribution
$priceSql = "SELECT 
    CASE 
        WHEN price BETWEEN 0 AND 500 THEN '₹0–₹500'
        WHEN price BETWEEN 501 AND 1000 THEN '₹501–₹1000'
        WHEN price BETWEEN 1001 AND 1500 THEN '₹1001–₹1500'
        ELSE '₹1501+' 
    END AS price_range, COUNT(*) AS count 
    FROM products GROUP BY price_range";
$priceResult = $conn->query($priceSql);

$priceRanges = [];
$priceCounts = [];

if ($priceResult && $priceResult->num_rows > 0) {
    while ($row = $priceResult->fetch_assoc()) {
        $priceRanges[] = $row['price_range'];
        $priceCounts[] = $row['count'];
    }
}

// Product Count by Type
$typeSql = "SELECT type, COUNT(*) AS count FROM products GROUP BY type";
$typeResult = $conn->query($typeSql);

$productTypes = [];
$typeCounts = [];

if ($typeResult && $typeResult->num_rows > 0) {
    while ($row = $typeResult->fetch_assoc()) {
        $productTypes[] = $row['type'];
        $typeCounts[] = $row['count'];
    }
}

// Product Count by Brand
$brandSql = "SELECT brand, COUNT(*) AS count FROM products GROUP BY brand";
$brandResult = $conn->query($brandSql);

$productBrands = [];
$brandCounts = [];

if ($brandResult && $brandResult->num_rows > 0) {
    while ($row = $brandResult->fetch_assoc()) {
        $productBrands[] = $row['brand'];
        $brandCounts[] = $row['count'];
    }
}

// Products by Style
$styleSql = "SELECT style, COUNT(*) AS count FROM products GROUP BY style";
$styleResult = $conn->query($styleSql);

$productStyles = [];
$styleCounts = [];

if ($styleResult && $styleResult->num_rows > 0) {
    while ($row = $styleResult->fetch_assoc()) {
        $productStyles[] = $row['style'];
        $styleCounts[] = $row['count'];
    }
}

// Price Comparison by Brand
$brandPriceSql = "SELECT brand, AVG(price) AS avg_price FROM products GROUP BY brand";
$brandPriceResult = $conn->query($brandPriceSql);

$priceComparisonBrands = [];
$averagePrices = [];

if ($brandPriceResult && $brandPriceResult->num_rows > 0) {
    while ($row = $brandPriceResult->fetch_assoc()) {
        $priceComparisonBrands[] = $row['brand'];
        $averagePrices[] = $row['avg_price'];
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Reports</title>
    <link rel="stylesheet" href="../css/form.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <h1>Product Reports</h1>
            </header>

            <!-- Price Distribution -->
            <section class="chart-container" style="width: 500px; margin: 0 auto;">
                <h2>Price Distribution</h2>
                <canvas id="priceChart"></canvas>
            </section>

            <!-- Product Count by Type -->
            <section class="chart-container" style="width: 500px; margin: 0 auto;">
                <h2>Product Count by Type</h2>
                <canvas id="typeChart"></canvas>
            </section>

            <!-- Product Count by Brand -->
            <section class="chart-container" style="width: 500px; margin: 0 auto;">
                <h2>Product Count by Brand</h2>
                <canvas id="brandChart"></canvas>
            </section>

            <!-- Products by Style -->
            <section class="chart-container" style="width: 500px; margin: 0 auto;">
                <h2>Products by Style</h2>
                <canvas id="styleChart"></canvas>
            </section>

            <!-- Price Comparison by Brand -->
            <section class="chart-container" style="width: 500px; margin: 0 auto;">
                <h2>Price Comparison by Brand</h2>
                <canvas id="brandPriceChart"></canvas>
            </section>
        </main>
    </div>

    <script>
        // Price Distribution
        const priceRanges = <?php echo json_encode($priceRanges); ?>;
        const priceCounts = <?php echo json_encode($priceCounts); ?>;
        new Chart(document.getElementById('priceChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: priceRanges,
                datasets: [{
                    label: 'Number of Products',
                    data: priceCounts,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            }
        });

        // Product Count by Type
        const productTypes = <?php echo json_encode($productTypes); ?>;
        const typeCounts = <?php echo json_encode($typeCounts); ?>;
        new Chart(document.getElementById('typeChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: productTypes,
                datasets: [{
                    data: typeCounts,
                    backgroundColor: ['#4caf50', '#ff9800', '#2196f3', '#e91e63']
                }]
            }
        });

        // Product Count by Brand
        const productBrands = <?php echo json_encode($productBrands); ?>;
        const brandCounts = <?php echo json_encode($brandCounts); ?>;
        new Chart(document.getElementById('brandChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: productBrands,
                datasets: [{
                    label: 'Number of Products',
                    data: brandCounts,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            }
        });

        // Products by Style
        const productStyles = <?php echo json_encode($productStyles); ?>;
        const styleCounts = <?php echo json_encode($styleCounts); ?>;
        new Chart(document.getElementById('styleChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: productStyles,
                datasets: [{
                    data: styleCounts,
                    backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe', '#ffce56']
                }]
            }
        });

        // Price Comparison by Brand
        const priceComparisonBrands = <?php echo json_encode($priceComparisonBrands); ?>;
        const averagePrices = <?php echo json_encode($averagePrices); ?>;
        new Chart(document.getElementById('brandPriceChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: priceComparisonBrands,
                datasets: [{
                    label: 'Average Price',
                    data: averagePrices,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 2,
                    tension: 0.1
                }]
            }
        });
    </script>
</body>
</html>
