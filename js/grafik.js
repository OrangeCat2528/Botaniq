// Define the DEFAULT_FONT_OPTIONS constant at the beginning of the script
const DEFAULT_FONT_OPTIONS = {
  color: 'black',
  size: 13,
  family: 'Montserrat'
};

// Stats arrays to store historical data
let tempHistory = [];
let humidHistory = [];
let soilHistory = [];

// Elemen dan grafik
const chartContexts = [
  document.getElementById('chart-1').getContext('2d'),
  document.getElementById('chart-2').getContext('2d'),
  document.getElementById('chart-3').getContext('2d'),
];

const chartConfigs = [
  { label: 'Temperature', yMin: 0, yMax: 100, color: '#32a4ea' },
  { label: 'Humidity', yMin: 0, yMax: 100, color: '#6cbe77' },
  { label: 'Soil Moisture', yMin: 0, yMax: 100, color: '#fb9700' }
];

const charts = chartContexts.map((ctx, index) => createChart(ctx, chartConfigs[index]));

// Variabel untuk menyimpan data terakhir yang diterima
let lastData = null;

// Function to calculate statistics
function calculateStats(values) {
    if (!values || values.length === 0) return { min: 0, max: 0, avg: 0 };
    const min = Math.min(...values);
    const max = Math.max(...values);
    const avg = (values.reduce((a, b) => a + b, 0) / values.length).toFixed(1);
    return { min, max, avg };
}

// Function to update statistics display
function updateStatsDisplay(type, value, stats) {
    // Update current value
    document.getElementById(`current-${type}`).textContent = `${value}${type === 'temp' ? '°C' : '%'}`;
    
    // Update min, max, avg
    document.getElementById(`min-${type}`).textContent = `${stats.min}${type === 'temp' ? '°C' : '%'}`;
    document.getElementById(`max-${type}`).textContent = `${stats.max}${type === 'temp' ? '°C' : '%'}`;
    document.getElementById(`avg-${type}`).textContent = `Avg: ${stats.avg}${type === 'temp' ? '°C' : '%'}`;
    
    // Update timestamp
    document.getElementById(`${type}-timestamp`).textContent = new Date().toLocaleTimeString();
    
    // Update status based on thresholds
    const status = document.getElementById(`${type}-status`);
    const thresholds = {
        temp: [20, 40],
        humid: [50, 80],
        soil: [50, 80]
    };
    
    const [min, max] = thresholds[type];
    if (value >= min && value <= max) {
        status.textContent = 'Normal';
        status.className = 'font-medium text-green-500';
    } else {
        status.textContent = 'Warning';
        status.className = 'font-medium text-yellow-500';
    }
}

// Fungsi untuk memperbarui grafik
function updateCharts(payload) {
    // Periksa jika data baru berbeda dari data terakhir
    if (JSON.stringify(payload) === JSON.stringify(lastData)) {
        return; // Tidak ada perubahan data, tidak perlu memperbarui grafik
    }

    lastData = payload; // Update data terakhir

    const dataArrays = [
        charts[0].data.datasets[0].data,
        charts[1].data.datasets[0].data,
        charts[2].data.datasets[0].data
    ];

    const labelsArrays = [
        charts[0].data.labels,
        charts[1].data.labels,
        charts[2].data.labels
    ];

    // Update data arrays and stats
    if (payload.temp !== undefined) {
        updateChartDataAndLabels(dataArrays[0], labelsArrays[0], payload.temp);
        tempHistory.push(payload.temp);
        if (tempHistory.length > 30) tempHistory.shift();
        updateStatsDisplay('temp', payload.temp, calculateStats(tempHistory));
    }

    if (payload.humidity !== undefined) {
        updateChartDataAndLabels(dataArrays[1], labelsArrays[1], payload.humidity);
        humidHistory.push(payload.humidity);
        if (humidHistory.length > 30) humidHistory.shift();
        updateStatsDisplay('humid', payload.humidity, calculateStats(humidHistory));
    }

    if (payload.soil !== undefined) {
        updateChartDataAndLabels(dataArrays[2], labelsArrays[2], payload.soil);
        soilHistory.push(payload.soil);
        if (soilHistory.length > 30) soilHistory.shift();
        updateStatsDisplay('soil', payload.soil, calculateStats(soilHistory));
    }

    charts.forEach(chart => chart.update());
}

// Ambil data dari API setiap interval waktu
function fetchData() {
  fetch('https://botaniq.cogarden.app/backend/load_data.php')
    .then(response => response.json())
    .then(data => {
      // Perbarui grafik hanya jika ada data baru
      updateCharts(data);
    })
    .catch(error => console.error('Error fetching data:', error));
}

// Mulai polling data
const fetchIntervalId = setInterval(fetchData, 500);

// Fungsi untuk membuat grafik
function createChart(ctx, { label, yMin, yMax, color }) {
  return new Chart(ctx, {
    type: 'line',
    data: {
      labels: [],
      datasets: [{
        label: label,
        data: [],
        borderColor: color,
        backgroundColor: `${color}80`, // Warna latar belakang dengan transparansi
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      plugins: {
        legend: {
          display: true,
          labels: {
            color: DEFAULT_FONT_OPTIONS.color,
            font: {
              size: DEFAULT_FONT_OPTIONS.size,
              family: DEFAULT_FONT_OPTIONS.family
            }
          }
        }
      },
      responsive: true,
      scales: {
        y: {
          min: yMin,
          max: yMax,
          ticks: {
            color: DEFAULT_FONT_OPTIONS.color,
            font: {
              size: DEFAULT_FONT_OPTIONS.size,
              family: DEFAULT_FONT_OPTIONS.family
            }
          },
          grid: {
            display: false
          }
        },
        x: {
          ticks: {
            color: DEFAULT_FONT_OPTIONS.color,
            font: {
              size: DEFAULT_FONT_OPTIONS.size,
              family: DEFAULT_FONT_OPTIONS.family
            }
          },
          grid: {
            display: false
          }
        }
      }
    }
  });
}

// Fungsi untuk memperbarui data dan label grafik
function updateChartDataAndLabels(dataArray, labelsArray, newValue) {
  if (dataArray.length >= 30) {
    dataArray.shift();
    labelsArray.shift();
  }

  dataArray.push(newValue);
  labelsArray.push(new Date().toLocaleTimeString());
}