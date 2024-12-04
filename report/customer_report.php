<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Update if needed
$dbname = "solesuite";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Customer Demographics (City Distribution)
$sql = "SELECT city, COUNT(*) as customer_count FROM customers GROUP BY city";
$result = $conn->query($sql);

$cities = [];
$cityCounts = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cities[] = $row['city'];
        $cityCounts[] = $row['customer_count'];
    }
}

// Customer Age Distribution
$sql = "
SELECT 
    CASE 
        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 18 AND 25 THEN '18-25'
        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 26 AND 35 THEN '26-35'
        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 36 AND 50 THEN '36-50'
        ELSE '50+' 
    END AS age_group, 
    COUNT(*) as customer_count 
FROM customers 
GROUP BY age_group";
$result = $conn->query($sql);

$ageGroups = [];
$ageCounts = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ageGroups[] = $row['age_group'];
        $ageCounts[] = $row['customer_count'];
    }
}

// Customer Signup Trends (Monthly)
$sql = "
    SELECT 
        DATE_FORMAT(registered_at, '%Y-%m') as signup_month, 
        COUNT(*) as signup_count 
    FROM customers 
    GROUP BY signup_month 
    ORDER BY signup_month";
$result = $conn->query($sql);

$months = [];
$monthlySignups = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $months[] = $row['signup_month'];
        $monthlySignups[] = $row['signup_count'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reports</title>
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
                <h1>Customer Reports</h1>
            </header>
            
            <!-- Customer Demographics -->
            <section class="chart-container" style="width: 400px; margin: 0 auto;">
                <h2>Customer Demographics by City</h2>
                <canvas id="cityChart" style="max-width: 100%; height: 300px;"></canvas>
            </section>

            <!-- Customer Age Distribution -->
            <section class="chart-container" style="width: 400px; margin: 0 auto;">
                <h2>Customer Age Distribution</h2>
                <canvas id="ageChart" style="max-width: 100%; height: 300px;"></canvas>
            </section>

            <!-- Customer Signup Trends -->
            <section class="chart-container" style="width: 400px; margin: 0 auto;">
                <h2>Customer Signup Trends</h2>
                <canvas id="signupChart" style="max-width: 100%; height: 300px;"></canvas>
            </section>

        </main>
    </div>

    <script>
        // Customer Demographics by City
        const cityLabels = <?php echo json_encode($cities); ?>;
        const cityData = <?php echo json_encode($cityCounts); ?>;

        new Chart(document.getElementById('cityChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: cityLabels,
                datasets: [{
                    data: cityData,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });

        // Customer Age Distribution
        const ageLabels = <?php echo json_encode($ageGroups); ?>;
        const ageData = <?php echo json_encode($ageCounts); ?>;

        new Chart(document.getElementById('ageChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ageLabels,
                datasets: [{
                    label: 'Number of Customers',
                    data: ageData,
                    backgroundColor: '#36A2EB',
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

        // Customer Signup Trends
        // Customer Signup Trends (Monthly)
        const monthlyLabels = <?php echo json_encode($months); ?>;
        const monthlyData = <?php echo json_encode($monthlySignups); ?>;

        new Chart(document.getElementById('signupChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Monthly Signups',
                    data: monthlyData,
                    borderColor: '#FF6384',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 2,
                    tension: 0.3
                }]
            },
            options: {
                scales: {
                    x: {
                        type: 'category',
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Signups'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });

    </script>
</body>
</html>
