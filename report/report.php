<?php
// Define variables if dynamic content is required (e.g., for admin info)
$adminName = "Admin"; // Replace with actual admin info if available
$logoPath = "../img/logo.png";
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
                <img src="<?php echo $logoPath; ?>" alt="Company Logo">
            </div>
            <div class="admin-info">
                <span>Welcome <?php echo htmlspecialchars($adminName); ?></span>
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
        
        <section class="erp-details-section" style="padding: 20px; background-color: #f9f9f9;">
            <div class="erp-details-container" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px;">
                <?php
                // Define report links dynamically
                $reports = [
                    ["Inventory Report", "inventory_report.php", "fas fa-warehouse"],
                    ["Customer Report", "customer_report.php", "fas fa-industry"],
                    ["Sales Report", "sales_report.php", "fas fa-chart-line"],
                    ["Supplier Report", "supplier_report.php", "fas fa-shipping-fast"],
                    // ["User Report", "user_report.php", "fas fa-shipping-fast"],
                    ["Product Report", "product_report.php", "fas fa-shipping-fast"]
                ];

                foreach ($reports as $report) {
                    echo "
                    <div class='feature-block' style='width: 200px; padding: 20px; text-align: center; border-radius: 8px; background-color: #91BDE5; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>
                        <i class='{$report[2]}' style='font-size: 24px; color: #333;'></i>
                        <a href='{$report[1]}' style='text-decoration: none; color: #333;'>
                            <h3 style='margin-top: 10px; font-size: 18px;'>{$report[0]}</h3>
                        </a>
                    </div>";
                }
                ?>
            </div>
        </section>
    </div>
</body>
</html>
