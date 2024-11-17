document.addEventListener('DOMContentLoaded', () => {
    const waterTank = document.getElementById('water-tank-container');
    const wavePath = document.getElementById('wave-path');
    const waterText = document.getElementById('water-text');
    const weatherContainer = document.getElementById('weather-container');

    const waterLevel = 60;

    const updateWaterTankSize = () => {
        const weatherWidth = weatherContainer.offsetWidth;
        waterTank.style.width = `${weatherWidth}px`;

        const containerHeight = weatherContainer.offsetHeight;
        waterTank.style.height = `${containerHeight}px`;

        const waveHeight = (containerHeight * waterLevel) / 100;
        const waveY = containerHeight - waveHeight;
        const d = `
            M0 ${waveY}
            Q ${weatherWidth / 4} ${waveY - 10}, ${weatherWidth / 2} ${waveY}
            T ${weatherWidth} ${waveY}
            T ${weatherWidth * 1.5} ${waveY}
            T ${weatherWidth * 2} ${waveY}
            V ${containerHeight}
            H 0
            Z
        `;
        wavePath.setAttribute('d', d);
        waterText.textContent = `Water Tank | ${waterLevel}%`;

        const textTop = waterText.offsetTop + waterText.offsetHeight / 2;
        if (textTop >= waveY) {
            waterText.classList.replace('text-blue-500', 'text-white');
        } else {
            waterText.classList.replace('text-white', 'text-blue-500');
        }
    };
    updateWaterTankSize();
    window.addEventListener('resize', updateWaterTankSize);
});
