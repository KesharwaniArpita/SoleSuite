<?php
// You can include session_start(), database connection, or other PHP logic here if needed
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoleSuite - Registration Form</title>
    <link rel="stylesheet" href="css/form.css">
    <style>
        .logout-container {
            max-width: 400px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .logout-container h2 {
            margin-bottom: 20px;
        }
        .logout-container p {
            margin-bottom: 30px;
            color: #555;
        }
        .logout-container button {
            width: 100%;
            padding: 10px;
            background-color: #91BDE5;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .logout-container button:hover {
            background-color: #91BDE5;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>

</head>
<body>
    <header class="main-header">
        <div class="header-content">
            <div class="logo">
                <img src="img/logo.png" alt="Company Logo">
            </div>
            <div class="admin-info">
                <span>SignIn</span>
                <img src="img/admin icon.png" alt="Admin Icon" class="admin-icon">
            </div>
        </div>
    </header>
    <div class="logout-container">
        <h2>You Have Logged Out</h2>
        <p>Thank you for using SoleSuite. To access your account again, please log back in.</p>
        <button onclick="window.location.href='Landing_page.php'">Return to Home</button>
    </div>
    <footer>
        <p>&copy; 2024 SoleSuite. All Rights Reserved.</p>
    </footer>
</body>
</html>