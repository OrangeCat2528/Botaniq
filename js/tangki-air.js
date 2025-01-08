// CSS
const waterTankStyles = `
@keyframes wave-back-and-forth {
    0% {
        transform: translateX(0);
    }
    50% {
        transform: translateX(-25%);
    }
    100% {
        transform: translateX(0);
    }
}

#wave-path {
    animation: wave-back-and-forth 4s ease-in-out infinite;
    fill: url(#waveGradient);
    transform-origin: center;
    will-change: transform;
}

#svg-container {
    overflow: hidden;
    position: absolute;
    bottom: 0;
    width: 200%;
    height: 100%;
}
`;

// Add styles to document
const styleSheet = document.createElement("style");
styleSheet.innerText = waterTankStyles;
document.head.appendChild(styleSheet);

// JavaScript Controller
class WaterTankController {
    constructor() {
        this.waterTank = document.getElementById('water-tank-container');
        this.svgContainer = document.getElementById('svg-container');
        this.wavePath = document.getElementById('wave-path');
        this.waterText = document.getElementById('water-text');
        this.weatherContainer = document.getElementById('weather-container');
        
        this.waterLevel = 0;
        this.currentWaterLevel = 0;
        this.animationFrame = null;
        
        this.setupEventListeners();
        this.startUpdates();
    }

    setupEventListeners() {
        window.addEventListener('resize', () => this.updateWaterTankSize());
    }

    updateWaterLevel(targetLevel) {
        if (this.animationFrame) {
            cancelAnimationFrame(this.animationFrame);
        }

        const animate = () => {
            const diff = targetLevel - this.currentWaterLevel;
            const step = diff * 0.1;

            if (Math.abs(diff) > 0.1) {
                this.currentWaterLevel += step;
                this.updateWaterTankSize();
                this.animationFrame = requestAnimationFrame(animate);
            } else {
                this.currentWaterLevel = targetLevel;
                this.updateWaterTankSize();
            }
        };

        this.animationFrame = requestAnimationFrame(animate);
    }

    updateWaterTankSize() {
        const weatherWidth = this.weatherContainer.offsetWidth;
        const containerHeight = this.weatherContainer.offsetHeight;

        // Update container dimensions
        this.waterTank.style.width = `${weatherWidth}px`;
        this.waterTank.style.height = `${containerHeight}px`;

        // Calculate wave parameters
        const waveHeight = (containerHeight * this.currentWaterLevel) / 100;
        const waveY = containerHeight - waveHeight;

        // Create a more natural looking wave path
        // Note: We're making the path wider because of the CSS animation
        const width = weatherWidth * 2; // Double width for animation
        const d = `
            M0 ${waveY}
            Q ${width * 0.25} ${waveY - 10}, ${width * 0.5} ${waveY}
            T ${width} ${waveY}
            V ${containerHeight}
            H 0
            Z
        `;
        this.wavePath.setAttribute('d', d);

        // Update water percentage text
        const waterPercentage = document.getElementById('water-percentage');
        if (waterPercentage) {
            const level = Math.round(this.currentWaterLevel);
            waterPercentage.textContent = `${level}%`;

            // Update color based on level
            if (level <= 20) {
                waterPercentage.className = 'font-medium text-red-600';
            } else if (level <= 40) {
                waterPercentage.className = 'font-medium text-orange-600';
            } else {
                waterPercentage.className = 'font-medium text-blue-600';
            }
        }
    }

    async fetchWaterTankData() {
        try {
            const response = await fetch('https://botaniq.cogarden.app/backend/load_data.php');
            const data = await response.json();
            
            if (data.watertank !== undefined) {
                const newLevel = parseInt(data.watertank, 10) || 0;
                if (newLevel !== this.waterLevel) {
                    this.waterLevel = newLevel;
                    this.updateWaterLevel(newLevel);
                }
            }
        } catch (error) {
            console.error('Error fetching watertank data:', error);
        }
    }

    startUpdates() {
        this.fetchWaterTankData();
        setInterval(() => this.fetchWaterTankData(), 5000);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new WaterTankController();
});