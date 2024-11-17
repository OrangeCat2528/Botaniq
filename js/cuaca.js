document.addEventListener('DOMContentLoaded', () => {
    const weatherContainer = document.getElementById('weather-container');
    const weatherText = document.getElementById('weather-text');
    const weatherIcon = document.getElementById('weather-icon');

    // Periksa apakah elemen ditemukan
    if (!weatherContainer || !weatherText || !weatherIcon) {
        console.error('Error: Weather elements not found in DOM.');
        return;
    }

    function fetchWeather(lat, lon) {
        const apiKey = 'd92a201b7291802904daf6dcb9444678';

        fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&units=metric&lang=id&appid=${apiKey}`)
            .then(response => response.json())
            .then(data => {
                const weatherMain = data.weather[0].main;
                const temperature = data.main.temp;

                weatherText.textContent = `${weatherMain} | ${temperature}°C`;
                switch (weatherMain.toLowerCase()) {
                    case 'clear':
                        weatherIcon.className = 'fas fa-sun text-xl mr-2';
                        weatherContainer.style.background = 'linear-gradient(to right, #FF8008, #FFC837)'; // Orange gradient
                        break;
                    case 'clouds':
                        weatherIcon.className = 'fas fa-cloud text-xl mr-2';
                        weatherContainer.style.background = 'linear-gradient(to right, #6a82fb, #fc5c7d)'; // Light blue gradient
                        break;
                    case 'rain':
                        weatherIcon.className = 'fas fa-cloud-rain text-xl mr-2';
                        weatherContainer.style.background = 'linear-gradient(to right, #00c6ff, #0072ff)'; // Blue gradient
                        break;
                    case 'thunderstorm':
                        weatherIcon.className = 'fas fa-bolt text-xl mr-2';
                        weatherContainer.style.background = 'linear-gradient(to right, #000000, #434343)'; // Dark gray gradient
                        break;
                    case 'snow':
                        weatherIcon.className = 'fas fa-snowflake text-xl mr-2';
                        weatherContainer.style.background = 'linear-gradient(to right, #E0EAFB, #C9D6FF)'; // White gradient
                        break;
                    default:
                        weatherIcon.className = 'fas fa-cloud text-xl mr-2';
                        weatherContainer.style.background = 'linear-gradient(to right, #d7d2cc, #304352)'; // Default gray gradient
                }
            })
            .catch(error => {
                console.error('Error fetching weather data:', error);
                weatherText.textContent = 'Unable to fetch weather data';
                weatherContainer.style.background = 'linear-gradient(to right, #d7d2cc, #304352)'; // Default gray gradient
            });
    }

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                fetchWeather(lat, lon);
            },
            (error) => {
                console.error('Error getting location:', error);
                weatherText.textContent = 'Location permission denied';
                weatherContainer.style.background = 'linear-gradient(to right, #d7d2cc, #304352)'; // Default gray gradient
            }
        );
    } else {
        console.error('Geolocation is not supported by this browser.');
        weatherText.textContent = 'Geolocation not supported';
        weatherContainer.style.background = 'linear-gradient(to right, #d7d2cc, #304352)'; // Default gray gradient
    }
});
