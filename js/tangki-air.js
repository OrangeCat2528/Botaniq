class WaterTankController {
    constructor() {
        this.waterTank = document.getElementById('water-tank-container');
        this.wavePath = document.getElementById('wave-path');
        this.waterText = document.getElementById('water-text');
        this.weatherContainer = document.getElementById('weather-container');
        
        this.waterLevel = 0;
        this.currentWaterLevel = 0; // For smooth animation
        this.animationFrame = null;
        
        this.setupEventListeners();
        this.startUpdates();
    }

    setupEventListeners() {
        window.addEventListener('resize', () => this.updateWaterTankSize());
    }

    updateWaterLevel(targetLevel) {
        // Cancel any existing animation
        if (this.animationFrame) {
            cancelAnimationFrame(this.animationFrame);
        }

        const animate = () => {
            // Smooth transition to target level
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

    getWaterLevelStatus(level) {
        if (level <= 20) return { color: 'red', text: 'Low' };
        if (level <= 40) return { color: 'orange', text: 'Warning' };
        return { color: 'green', text: 'Good' };
    }

    updateWaterTankSize() {
        const weatherWidth = this.weatherContainer.offsetWidth;
        this.waterTank.style.width = `${weatherWidth}px`;

        const containerHeight = this.weatherContainer.offsetHeight;
        this.waterTank.style.height = `${containerHeight}px`;

        // Calculate wave parameters
        const waveHeight = (containerHeight * this.currentWaterLevel) / 100;
        const waveY = containerHeight - waveHeight;
        const amplitude = Math.min(10, waveHeight * 0.1); // Dynamic amplitude based on water level

        // Create wave path with multiple curves for more natural look
        const d = `
            M0 ${waveY}
            Q ${weatherWidth * 0.2} ${waveY - amplitude} ${weatherWidth * 0.4} ${waveY}
            T ${weatherWidth * 0.8} ${waveY}
            T ${weatherWidth} ${waveY}
            V ${containerHeight}
            H 0
            Z
        `;
        this.wavePath.setAttribute('d', d);

        // Update water level text and status
        const status = this.getWaterLevelStatus(this.currentWaterLevel);
        const waterPercentage = document.getElementById('water-percentage');
        if (waterPercentage) {
            waterPercentage.textContent = `${Math.round(this.currentWaterLevel)}%`;
            waterPercentage.style.color = `var(--color-${status.color}-600)`;
        }

        // Update text color based on water level
        const textTop = this.waterText.offsetTop + this.waterText.offsetHeight / 2;
        if (textTop >= waveY) {
            this.waterText.classList.replace('text-blue-500', 'text-white');
            this.waterText.classList.replace('text-blue-700', 'text-white');
        } else {
            this.waterText.classList.replace('text-white', 'text-blue-700');
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
            } else {
                console.error('Watertank data not found in the response:', data);
            }
        } catch (error) {
            console.error('Error fetching watertank data:', error);
        }
    }

    startUpdates() {
        // Initial fetch
        this.fetchWaterTankData();
        
        // Regular updates
        setInterval(() => this.fetchWaterTankData(), 5000);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new WaterTankController();
});