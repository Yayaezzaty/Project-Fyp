<?php
session_start();
include 'db_connection.php'; // Database connection
// Fetch data for dashboard widgets
$totalSales = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) AS total FROM orders"))['total'] ?? 0;
$totalCustomers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'] ?? 0;
$totalBookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings"))['total'] ?? 0;
$lowStock = mysqli_query($conn, "SELECT name FROM products WHERE quantity <= 5");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --light-gray: #e9ecef;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f5f7fb;
            color: var(--dark);
        }
        
        .main-content {
            margin-left: 250px;
            padding: 30px;
            transition: all 0.3s;
        }
        
        header {
            margin-bottom: 30px;
        }
        
        header h1 {
            color: var(--dark);
            font-weight: 600;
            font-size: 28px;
        }
        
        /* Dashboard Widgets */
        .dashboard-widgets {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .widget {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
            border-left: 4px solid var(--primary);
        }
        
        .widget:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .widget:nth-child(1) {
            border-left-color: var(--success);
        }
        
        .widget:nth-child(2) {
            border-left-color: var(--warning);
        }
        
        .widget:nth-child(3) {
            border-left-color: var(--danger);
        }
        
        .widget h3 {
            font-size: 14px;
            color: var(--gray);
            margin-bottom: 10px;
            font-weight: 500;
        }
        
        .widget p {
            font-size: 24px;
            font-weight: 600;
            color: var(--dark);
        }
        
        /* Charts Section */
        .charts {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        
        .charts h3 {
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 18px;
        }
        
        /* Low Stock Section */
        .low-stock {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        
        .low-stock h3 {
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 18px;
            display: flex;
            align-items: center;
        }
        
        .low-stock h3::before {
            content: '';
            display: inline-block;
            width: 5px;
            height: 20px;
            background: var(--danger);
            margin-right: 10px;
            border-radius: 3px;
        }
        
        .low-stock ul {
            list-style: none;
        }
        
        .low-stock li {
            padding: 12px 15px;
            border-bottom: 1px solid var(--light-gray);
            display: flex;
            align-items: center;
        }
        
        .low-stock li:last-child {
            border-bottom: none;
        }
        
        .low-stock li::before {
            content: '\f06a';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: var(--danger);
            margin-right: 10px;
        }
        
        /* Filters Section */
        .filters {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        
        .filters h3 {
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 18px;
        }
        
        .filters form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }
        
        .filters label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: var(--gray);
        }
        
        .filters input, .filters select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--light-gray);
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .filters input:focus, .filters select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        .filters button {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .filters button:hover {
            background: var(--primary-dark);
        }
        
        /* Quick Actions */
        .quick-actions {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .quick-actions h3 {
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 18px;
        }
        
        .quick-actions button {
            background: white;
            color: var(--primary);
            border: 1px solid var(--primary);
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        
        .quick-actions button:hover {
            background: var(--primary);
            color: white;
        }
        
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            
            .dashboard-widgets {
                grid-template-columns: 1fr;
            }
            
            .filters form {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'admin_navigation.php'; ?>
    
    <div class="main-content">
        <header>
            <h1>Admin Dashboard</h1>
        </header>
        <section class="dashboard-widgets">
            <div class="widget">
                <h3>Total Sales</h3>
                <p>$<?php echo number_format($totalSales, 2); ?></p>
            </div>
            <div class="widget">
                <h3>Total Customers</h3>
                <p><?php echo $totalCustomers; ?></p>
            </div>
            <div class="widget">
                <h3>Total Bookings</h3>
                <p><?php echo $totalBookings; ?></p>
            </div>
        </section>
        <section class="charts">
            <h3>Sales Analytics</h3>
            <canvas id="salesChart"></canvas>
        </section>
        <section class="low-stock">
            <h3>Low Stock Alerts</h3>
            <ul>
                <?php while ($row = mysqli_fetch_assoc($lowStock)) { echo "<li>{$row['name']}</li>"; } ?>
            </ul>
        </section>
        <section class="quick-actions">
            <h3>Quick Actions</h3>
            <button onclick="location.href='admin_product.php'"><i class="fas fa-plus"></i> Add New Product</button>
            <button onclick="location.href='booking_admin.php'"><i class="fas fa-calendar-alt"></i> Manage Bookings</button>
            <button onclick="location.href='admin_report.php'"><i class="fas fa-file-alt"></i> Generate Report</button>
        </section>
    </div>

    <script>
        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April'],
                datasets: [{
                    label: 'Monthly Sales',
                    data: [1200, 1900, 3000, 5000],
                    backgroundColor: 'rgba(67, 97, 238, 0.1)',
                    borderColor: 'rgba(67, 97, 238, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>