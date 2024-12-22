<?php
require_once '../config/database.php'; 

// Fetching new users per day
$sql_new_users = "SELECT DATE(created_at) AS date, COUNT(*) AS num_users
                  FROM users
                  GROUP BY DATE(created_at)
                  ORDER BY date DESC";
$result_new_users = $conn->query($sql_new_users);

$new_users_per_day = [];
$dates = [];
$num_users = [];

while ($row = $result_new_users->fetch_assoc()) {
    $new_users_per_day[] = ['date' => $row['date'], 'num_users' => $row['num_users']];
    $dates[] = $row['date'];
    $num_users[] = $row['num_users'];
}

// Fetching total, active, and archived users
$sql_total_users = "SELECT COUNT(*) AS total_users FROM users";
$result_total_users = $conn->query($sql_total_users);
$row_total_users = $result_total_users->fetch_assoc();
$total_users = $row_total_users['total_users'];

$sql_active_users = "SELECT COUNT(*) AS active_users
                     FROM users
                     WHERE updated_at > NOW() - INTERVAL 30 DAY";
$result_active_users = $conn->query($sql_active_users);
$row_active_users = $result_active_users->fetch_assoc();
$active_users = $row_active_users['active_users'];

$sql_archived_users = "SELECT COUNT(*) AS archived_users FROM users WHERE is_archifed = 1";
$result_archived_users = $conn->query($sql_archived_users);
$row_archived_users = $result_archived_users->fetch_assoc();
$archived_users = $row_archived_users['archived_users'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Charts</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
        }
        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }
        .chart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .chart-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 15px;
            transition: transform 0.3s ease;
        }
        .chart-container:hover {
            transform: translateY(-5px);
        }
        canvas {
            max-width: 100%;
            height: auto !important;
        }
        @media (max-width: 768px) {
            .chart-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Admin Dashboard - User Statistics</h2>
        <div class="chart-grid">
            <div class="chart-container">
                <canvas id="newUsersChart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="totalUsersChart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="activeUsersChart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="archivedUsersChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        const totalUsers = <?php echo json_encode($total_users); ?>;
        const activeUsers = <?php echo json_encode($active_users); ?>;
        const archivedUsers = <?php echo json_encode($archived_users); ?>;
        const num_users = <?php echo json_encode($num_users); ?>;
        const dates = <?php echo json_encode($dates); ?>;

        // Create Bar Chart for New Users per Day
        new Chart(document.getElementById('newUsersChart'), {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [{
                    label: 'New Users per Day',
                    data: num_users,
                    backgroundColor: 'rgba(75, 192, 192, 0.8)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: { 
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' users';
                            }
                        }
                    }
                }
            }
        });

        // Create Doughnut Chart for Total Users
        new Chart(document.getElementById('totalUsersChart'), {
            type: 'doughnut',
            data: {
                labels: ['Total Users', 'Remaining'],
                datasets: [{
                    data: [totalUsers, 100 - totalUsers],
                    backgroundColor: ['rgba(153, 102, 255, 0.8)', 'rgba(211, 211, 211, 0.2)'],
                    borderColor: ['rgba(153, 102, 255, 1)', 'rgba(211, 211, 211, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' users';
                            }
                        }
                    }
                },
                cutout: '70%',
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });

        // Create Doughnut Chart for Active Users
        new Chart(document.getElementById('activeUsersChart'), {
            type: 'doughnut',
            data: {
                labels: ['Active Users', 'Inactive Users'],
                datasets: [{
                    data: [activeUsers, Math.max(0, totalUsers - activeUsers)],
                    backgroundColor: ['rgba(255, 159, 64, 0.8)', 'rgba(211, 211, 211, 0.2)'],
                    borderColor: ['rgba(255, 159, 64, 1)', 'rgba(211, 211, 211, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' users';
                            }
                        }
                    }
                },
                cutout: '70%',
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });

        // Create Doughnut Chart for Archived Users
        new Chart(document.getElementById('archivedUsersChart'), {
            type: 'doughnut',
            data: {
                labels: ['Archived Users', 'Active Users'],
                datasets: [{
                    data: [archivedUsers, Math.max(0, totalUsers - archivedUsers)],
                    backgroundColor: ['rgba(255, 99, 132, 0.8)', 'rgba(211, 211, 211, 0.2)'],
                    borderColor: ['rgba(255, 99, 132, 1)', 'rgba(211, 211, 211, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' users';
                            }
                        }
                    }
                },
                cutout: '70%',
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
    </script>
</body>
</html>
