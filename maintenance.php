<?php
require_once 'helper/connection.php';
require_once 'layout/top.php';

// Maintenance Configuration Class
class MaintenanceConfig {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function getMaintenanceStatus() {
        $query = "SELECT * FROM maintenance_mode ORDER BY id DESC LIMIT 1";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_assoc($result);
    }
}

// Get maintenance status
$maintenanceConfig = new MaintenanceConfig($connection);
$maintenanceStatus = $maintenanceConfig->getMaintenanceStatus();

// If no maintenance record exists or maintenance is off, redirect to home
if (!$maintenanceStatus || !$maintenanceStatus['is_maintenance']) {
    header('Location: /');
    exit();
}
?>

<div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 px-4">
    <!-- Warning Icon -->
    <div class="mb-8">
        <img src="/assets/img/superapp-login-logo-only.png" alt="Botaniq SuperApp Logo" class="w-32 h-32 mx-auto">
    </div>

    <!-- Maintenance Message -->
    <div class="bg-white rounded-3xl shadow-lg p-8 max-w-lg w-full text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">
            System Maintenance
        </h1>
        
        <div class="mb-6">
            <p class="text-gray-600 mb-2">
                <?php echo htmlspecialchars($maintenanceStatus['maintenance_message']); ?>
            </p>
            <p class="text-gray-600">
                Please check back in a few moments.
            </p>
        </div>

        <!-- Estimated Time -->
        <div class="bg-gray-50 rounded-xl p-4 mb-6">
            <p class="text-sm text-gray-500">Estimated completion time</p>
            <p class="text-lg font-bold text-gray-700"><?php echo htmlspecialchars($maintenanceStatus['estimated_hours']); ?> Hours</p>
        </div>

        <!-- Status Indicators -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-yellow-50 rounded-xl p-4">
                <i class="fas fa-server text-yellow-500 mb-2"></i>
                <p class="text-sm font-medium text-yellow-600"><?php echo htmlspecialchars($maintenanceStatus['system_status']); ?></p>
            </div>
            <div class="bg-blue-50 rounded-xl p-4">
                <i class="fas fa-database text-blue-500 mb-2"></i>
                <p class="text-sm font-medium text-blue-600"><?php echo htmlspecialchars($maintenanceStatus['database_status']); ?></p>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="text-sm text-gray-500">
            <p>Need assistance? Contact support:</p>
            <p class="font-medium text-gray-700"><?php echo htmlspecialchars($maintenanceStatus['support_email']); ?></p>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-8 text-center text-sm text-gray-500">
        <p>We apologize for any inconvenience</p>
    </div>
</div>

<body>
    <noscript>
        <p>If you are seeing this, JavaScript is disabled or the redirection failed. Please enable JavaScript or click 
            <a href="auth/login">here</a> to log in.</p>
    </noscript>
</body>
</html>