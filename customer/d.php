<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoleSuite - Customer Details</title>
    <link rel="stylesheet" href="../css/details.css"> <!-- Keeping external CSS link for reference -->
</head>
<body style="font-family: 'Arial', sans-serif; background-color: #f0f0f0; margin: 0; padding: 0;">
    <header class="main-header" style="padding: 20px; background-color: #ffffff; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ffffff;">
        <div class="header-content" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <div class="logo">
                <img src="img/logo.png" alt="Company Logo" style="width: 120px;">
            </div>
            <div class="admin-info" style="display: flex; align-items: center; background-color: #91BDE5; padding: 10px; border-radius: 20px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
                <span style="font-size: 1rem; color: #555; margin-right: 10px;">Welcome admin</span>
                <img src="img/admin-icon.png" alt="Admin Icon" class="admin-icon" style="width: 40px; border-radius: 50%;">
            </div>
        </div>
    </header>
    <hr>
    <div class="container" style="display: flex; min-height: 100vh;">
        <aside class="sidebar" style="width: 20%; background-color: #ffffff; padding: 20px; display: flex; flex-direction: column;">
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="margin: 15px 0;">
                    <a href="../customer/CRM.html" style="display: flex; align-items: center; color: #333; text-decoration: none; padding: 10px; transition: background-color 0.3s, color 0.3s; border-radius: 5px;">
                        <img src="img/i1.png" alt="Customer Manage Icon" style="width: 19px; height: 18px; margin-right: 10px;">
                        <span style="font-size: 1rem;">Customer Manage</span>
                    </a>
                </li>
                <li style="margin: 15px 0;">
                    <a href="../customer/CRM.html" style="display: flex; align-items: center; color: #333; text-decoration: none; padding: 10px; transition: background-color 0.3s, color 0.3s; border-radius: 5px;">
                        <img src="img/i2.png" alt="Product Manage Icon" style="width: 19px; height: 18px; margin-right: 10px;">
                        <span style="font-size: 1rem;">Product Manage</span>
                    </a>
                </li>
                <li style="margin: 15px 0;">
                    <a href="../customer/CRM.html" style="display: flex; align-items: center; color: #333; text-decoration: none; padding: 10px; transition: background-color 0.3s, color 0.3s; border-radius: 5px;">
                        <img src="img/i3.png" alt="Supplier Manage Icon" style="width: 19px; height: 18px; margin-right: 10px;">
                        <span style="font-size: 1rem;">Supplier Manage</span>
                    </a>
                </li>
                <li style="margin: 15px 0;">
                    <a href="../customer/CRM.html" style="display: flex; align-items: center; color: #333; text-decoration: none; padding: 10px; transition: background-color 0.3s, color 0.3s; border-radius: 5px;">
                        <img src="img/i4.png" alt="Inventory Manage Icon" style="width: 19px; height: 18px; margin-right: 10px;">
                        <span style="font-size: 1rem;">Inventory Manage</span>
                    </a>
                </li>
                <li style="margin: 15px 0;">
                    <a href="../customer/CRM.html" style="display: flex; align-items: center; color: #333; text-decoration: none; padding: 10px; transition: background-color 0.3s, color 0.3s; border-radius: 5px;">
                        <img src="img/i5.png" alt="User Manage Icon" style="width: 19px; height: 18px; margin-right: 10px;">
                        <span style="font-size: 1rem;">User Manage</span>
                    </a>
                </li>
                <li style="margin: 15px 0;">
                    <a href="report.html" style="display: flex; align-items: center; color: #333; text-decoration: none; padding: 10px; transition: background-color 0.3s, color 0.3s; border-radius: 5px;">
                        <img src="img/i6.png" alt="Report Icon" style="width: 19px; height: 18px; margin-right: 10px;">
                        <span style="font-size: 1rem;">Report</span>
                    </a>
                </li>
                <li style="margin: 15px 0;">
                    <a href="Logout" style="display: flex; align-items: center; color: #333; text-decoration: none; padding: 10px; transition: background-color 0.3s, color 0.3s; border-radius: 5px;">
                        <img src="img/i7.png" alt="Logout Icon" style="width: 19px; height: 18px; margin-right: 10px;">
                        <span style="font-size: 1rem;">Logout</span>
                    </a>
                </li>
            </ul>
        </aside>

        <main style="width: 80%; padding: 20px; background-color: #fff;">
            <header class="top-bar" style="display: flex; justify-content: space-between; align-items: center;">
                <div class="user-info">
                    <button class="add-btn" style="padding: 10px 20px; border: none; border-radius: 20px; background-color: #91BDE5; color: white; cursor: pointer; font-size: 16px; transition: background-color 0.3s;">ADD</button>
                </div>
                <div class="search-bar">
                    <input type="text" placeholder="Search" style="padding: 10px; border: 1px solid #91BDE5; border-radius: 5px; background-color: #DAE0DB;">
                    <button style="padding: 10px 20px; border: none; background-color: #91BDE5; color: white; border-radius: 5px; cursor: pointer;">Search</button>
                </div>
            </header>
            <section class="registration-form" style="width: 100%; margin-top: 20px; padding: 20px; background-color: #E4E8E5; border-radius: 10px;">
                <div class="form-header" style="padding-left: 300px; padding-bottom: 20px; text-align: center;">
                    <h2 style="text-align: center;">CUSTOMER C001 INFORMATION</h2>
                </div>

                <form style="display: grid; grid-template-columns: 0.2fr 0.6fr; gap: 10px;">
                    <label style="font-weight: bold; color: #333;">Name</label>
                    <input type="text" value="Julian" readonly style="padding: 10px; border: 1px solid #DAE0DB; border-radius: 5px; background-color: #DAE0DB; color: #333; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
                    
                    <label style="font-weight: bold; color: #333;">User Role</label>
                    <input type="text" value="Customer" readonly style="padding: 10px; border: 1px solid #DAE0DB; border-radius: 5px; background-color: #DAE0DB; color: #333; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
                    
                    <label style="font-weight: bold; color: #333;">Mobile</label>
                    <input type="text" value="8822445500" readonly style="padding: 10px; border: 1px solid #DAE0DB; border-radius: 5px; background-color: #DAE0DB; color: #333; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
                    
                    <label style="font-weight: bold; color: #333;">Email</label>
                    <input type="text" value="sda@fasfa.com" readonly style="padding: 10px; border: 1px solid #DAE0DB; border-radius: 5px; background-color: #DAE0DB; color: #333; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
                    
                    <label style="font-weight: bold; color: #333;">Address</label>
                    <input type="text" value="23134 LHP" readonly style="padding: 10px; border: 1px solid #DAE0DB; border-radius: 5px; background-color: #DAE0DB; color: #333; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
                    
                    <label style="font-weight: bold; color: #333;">Date of Birth</label>
                    <input type="text" value="15-8-2002" readonly style="padding: 10px; border: 1px solid #DAE0DB; border-radius: 5px; background-color: #DAE0DB; color: #333; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
                    
                    <label style="font-weight: bold; color: #333;">Country</label>
                    <input type="text" value="Vietnam" readonly style="padding: 10px; border: 1px solid #DAE0DB; border-radius: 5px; background-color: #DAE0DB; color: #333; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
                    
                    <label style="font-weight: bold; color: #333;">City</label>
                    <input type="text" value="HCM" readonly style="padding: 10px; border: 1px solid #DAE0DB; border-radius: 5px; background-color: #DAE0DB; color: #333; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
                    
                    <div class="form-buttons" style="grid-column: span 2; display: flex; justify-content: space-between; margin-top: 20px;">
                        <button type="button" class="close-btn" style="padding: 10px 20px; border: none; border-radius: 20px; background-color: #ff4d4d; color: white; cursor: pointer;">CLOSE</button>
                        <button type="button" class="register-btn" style="padding: 10px 20px; border: none; border-radius: 20px; background-color: #91BDE5; color: white; cursor: pointer;">UPDATE</button>
                        <button type="button" class="register-btn delete-btn" style="padding: 10px 20px; border: none; border-radius: 20px; background-color: #ff4d4d; color: white; cursor: pointer;">DELETE</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
