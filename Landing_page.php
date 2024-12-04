<?php
// Variables for dynamic content (customize as needed)
$pageTitle = "Solesuit ERP";
$companyName = "Solesuit";
$year = date("Y");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="css/CSS.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <!-- Section 1: Hero Section -->
    <header class="hero">
        <nav>
            <div class="logo"><img src="img/logo2.png" alt="Company Logo"></div>
            <ul class="nav-links">
                <!-- <li><a href="#">Home</a></li>
                <li><a href="#">Features</a></li>
                <li><a href="#">Pricing</a></li>
                <li><a href="#">Contact</a></li> -->
                <li><a href="registration.php" class="login-btn">Register</a></li>
                <li><a href="login.php" class="login-btn">Login</a></li>
            </ul>
        </nav>
        <div class="hero-content">
            <h1 style="color: black;">Transform your shoe business with our ERP solution</h1>
            <form class="email-form">
                <input type="email" placeholder="Enter your email to get started" required>
                <a href="login.php" target="_blank">
                    <button type="submit">Try it Yourself</button>
                </a>
            </form>
            <div>
                <img src="img/s1.jpg" alt="Hero Image">
            </div>
        </div>
    </header>

    <!-- Section 2: Features -->
    <section class="features-section">
        <div class="features-container">
            <h2>Our Features</h2>
            <div class="features-list">
                <div class="feature-item">
                    <i class="fas fa-box"></i>
                    <h3>Inventory Management</h3>
                    <p>Keep track of your inventory and manage it with ease.</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-receipt"></i>
                    <h3>Order Processing</h3>
                    <p>Automate and streamline your order processing system.</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-cogs"></i>
                    <h3>Manufacturing Scheduling</h3>
                    <p>Schedule and manage your production efficiently.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="call-to-action">
        <h2>The future of your shoe business starts here. Join us today!</h2>
        <a href="registration.php" ><button class="get-started-btn">Get Started</button></a>
    </section>

    <!-- Section 3: ERP Details -->
    <section class="erp-details-section">
        <div class="erp-details-container">
            <div class="feature-block">
                <i class="fas fa-warehouse"></i>
                <h3>Inventory Management</h3>
                <p>Track your stock in real-time and never run out of essential products. Our system ensures you stay on top of your inventory levels at all times.</p>
                <p style="color: black;"><u>Manage Inventory</u></p>
            </div>
            <div class="feature-block grey-background">
                <i class="fas fa-industry"></i>
                <h3>Manufacturing Scheduling</h3>
                <p>Plan your production with precision. Our tool helps you align manufacturing with sales demand for seamless operations.</p>
                <p style="color: black;"><u>Automate Production</u></p>
            </div>
            <div class="feature-block">
                <i class="fas fa-chart-line"></i>
                <h3>Sales Analysis</h3>
                <p>Analyze your sales patterns with detailed reports and make data-driven decisions to boost revenue and efficiency.</p>
                <p style="color: black;"><u>Analyze sales</u></p>
            </div>
            <div class="feature-block grey-background">
                <i class="fas fa-shipping-fast"></i>
                <h3>Supplier Integration</h3>
                <p>Integrate with suppliers to streamline orders, manage deliveries, and maintain strong relationships with key partners.</p>
                <p style="color: black;"><u>Connect with Supplier</u></p>
            </div>
        </div>
    </section>

    <!-- Section 4: Customer Feedback -->
    <section class="customer-feedback-section">
        <h2>Follow in the footsteps of successful shoe industry leaders</h2>
        <p>Keep track of your inventory and manage it with ease.</p>
        <div class="feedback-image">
            <img src="img/s2.jpg" alt="Feedback">
        </div>
    </section>

    <!-- Section 5: Solesuit Advantages -->
    <section class="advantages-section">
        <h2>Solesuit ERP infrastructure. Fast. Reliable. Secure.</h2>
        <div class="advantages-grid">
            <p>Supercharge your operations with blazing-fast processing speeds and virtually zero downtime, keeping your business running at peak performance 24/7!</p>
            <p>Unmatched reliability with around-the-clock support, ensuring you're never alone, and frequent updates that keep you ahead of the competition.</p>
            <p>State-of-the-art, industry-leading security protocols that provide bulletproof protection for your data at every step, giving you peace of mind to focus on growth!</p>
        </div>
    </section>

    <!-- Section 6: Big Image -->
    <section class="image-section">
        <img src="img/s3.jpg" alt="ERP Showcase" width="100%">
    </section>

    <!-- Section 7: Call to Action -->
    <section class="call-to-action">
        <h2>Power your selling with <?php echo $companyName; ?></h2>
        <a href="registration.php" ><button class="get-started-btn">Get Started</button></a>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo $year; ?> <?php echo $companyName; ?>. All Rights Reserved.</p>
    </footer>
</body>

</html>
