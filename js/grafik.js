// Define the DEFAULT_FONT_OPTIONS constant at the beginning of the script
const DEFAULT_FONT_OPTIONS = {
  color: 'black',
  size: 13,
  family: 'Montserrat'
};

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

  updateChartDataAndLabels(dataArrays[0], labelsArrays[0], payload.temp);
  updateChartDataAndLabels(dataArrays[1], labelsArrays[1], payload.humidity);
  updateChartDataAndLabels(dataArrays[2], labelsArrays[2], payload.soil)

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
