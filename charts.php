<?php
require_once './layout/top.php';
require_once './layout/header.php';
require_once './layout/sidebar.php';
?>

<!-- Charts Section -->
<div class="px-4 py-6">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-800 text-left">Live Monitoring</h1>
                <p class="text-sm text-gray-500 text-left">Real-time sensor data</p>
            </div>
            <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-gray-100">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-sm text-gray-600">Live Updates</span>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="konten-grafik">
        <!-- Temperature Chart -->
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800">Temperature</h3>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-2xl font-bold text-blue-500" id="current-temp">--°C</span>
                        <span class="text-sm text-gray-500" id="avg-temp">Avg: --°C</span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-400">Last update</div>
                    <div class="text-sm text-gray-600" id="temp-timestamp">--:--</div>
                </div>
            </div>
            <div class="p-4">
                <canvas id="chart-1" style="height: 40vh; width: 100%;"></canvas>
            </div>
            <div class="px-6 py-3 bg-gray-50 flex items-center justify-between text-sm">
                <div class="grid grid-cols-3 gap-4 flex-1">
                    <div>
                        <div class="text-xs text-gray-500">Min</div>
                        <div class="font-medium text-gray-700" id="min-temp">--°C</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Max</div>
                        <div class="font-medium text-gray-700" id="max-temp">--°C</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Status</div>
                        <div class="font-medium text-green-500" id="temp-status">Normal</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Humidity Chart -->
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800">Humidity</h3>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-2xl font-bold text-green-500" id="current-humid">--%</span>
                        <span class="text-sm text-gray-500" id="avg-humid">Avg: --%</span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-400">Last update</div>
                    <div class="text-sm text-gray-600" id="humid-timestamp">--:--</div>
                </div>
            </div>
            <div class="p-4">
                <canvas id="chart-2" style="height: 40vh; width: 100%;"></canvas>
            </div>
            <div class="px-6 py-3 bg-gray-50 flex items-center justify-between text-sm">
                <div class="grid grid-cols-3 gap-4 flex-1">
                    <div>
                        <div class="text-xs text-gray-500">Min</div>
                        <div class="font-medium text-gray-700" id="min-humid">--%</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Max</div>
                        <div class="font-medium text-gray-700" id="max-humid">--%</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Status</div>
                        <div class="font-medium text-green-500" id="humid-status">Normal</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Soil Moisture Chart -->
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800">Soil Moisture</h3>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-2xl font-bold text-yellow-500" id="current-soil">--%</span>
                        <span class="text-sm text-gray-500" id="avg-soil">Avg: --%</span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-400">Last update</div>
                    <div class="text-sm text-gray-600" id="soil-timestamp">--:--</div>
                </div>
            </div>
            <div class="p-4">
                <canvas id="chart-3" style="height: 40vh; width: 100%;"></canvas>
            </div>
            <div class="px-6 py-3 bg-gray-50 flex items-center justify-between text-sm">
                <div class="grid grid-cols-3 gap-4 flex-1">
                    <div>
                        <div class="text-xs text-gray-500">Min</div>
                        <div class="font-medium text-gray-700" id="min-soil">--%</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Max</div>
                        <div class="font-medium text-gray-700" id="max-soil">--%</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Status</div>
                        <div class="font-medium text-green-500" id="soil-status">Normal</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="js/grafik.js?v=2"></script>

<!-- Live Update Script -->
<script>
function updateTimestamp(elementId) {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', { 
        hour: '2-digit', 
        minute: '2-digit',
        second: '2-digit',
        hour12: false 
    });
    document.getElementById(elementId).textContent = timeString;
}

function calculateAverage(values) {
    if (!values.length) return 0;
    return (values.reduce((a, b) => a + b, 0) / values.length).toFixed(1);
}

function updateStats(values, prefix) {
    if (values && values.length) {
        const min = Math.min(...values);
        const max = Math.max(...values);
        const avg = calculateAverage(values);
        const current = values[values.length - 1];

        document.getElementById(`current-${prefix}`).textContent = `${current}${prefix === 'temp' ? '°C' : '%'}`;
        document.getElementById(`min-${prefix}`).textContent = `${min}${prefix === 'temp' ? '°C' : '%'}`;
        document.getElementById(`max-${prefix}`).textContent = `${max}${prefix === 'temp' ? '°C' : '%'}`;
        document.getElementById(`avg-${prefix}`).textContent = `Avg: ${avg}${prefix === 'temp' ? '°C' : '%'}`;

        // Update timestamp
        updateTimestamp(`${prefix}-timestamp`);

        // Update status based on thresholds (customize as needed)
        const status = document.getElementById(`${prefix}-status`);
        if (current >= min && current <= max) {
            status.textContent = 'Normal';
            status.className = 'font-medium text-green-500';
        } else {
            status.textContent = 'Warning';
            status.className = 'font-medium text-yellow-500';
        }
    }
}

// This function would be called whenever new data arrives
function onNewData(data) {
    // Update statistics for each metric
    updateStats(data.tempValues, 'temp');
    updateStats(data.humidValues, 'humid');
    updateStats(data.soilValues, 'soil');
}
</script>

<div class="h-24"></div>


<?php
require_once './layout/bottom.php';
?>
</body>
</html>