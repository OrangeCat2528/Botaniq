document.addEventListener('DOMContentLoaded', () => {
    const weatherContainer = document.getElementById('weather-container');
    const weatherText = document.getElementById('weather-text');
    const weatherIcon = document.getElementById('weather-icon');

    if (!weatherContainer || !weatherText || !weatherIcon) {
        console.error('Error: Weather elements not found in DOM.');
        return;
    }

    function fetchWeather(lat, lon) {
        fetch(`https://botaniq.cogarden.app/helper/weather_helper.php?lat=${encodeURIComponent(lat)}&lon=${encodeURIComponent(lon)}`)
            .then(response => response.json())
            .then(data => {
                const weatherMain = data.weather[0].main;
                const temperature = data.main.temp;

                weatherText.textContent = `${weatherMain} | ${temperature}°C`;
                switch (weatherMain.toLowerCase()) {
                    case 'clear':
                        weatherIcon.className = 'fas fa-sun text-xl mr-2';
                        weatherContainer.style.background = 'linear-gradient(to right, #FF8008, #FFC837)';
                        break;
                    case 'clouds':
                        weatherIcon.className = 'fas fa-cloud text-xl mr-2';
                        weatherContainer.style.background = 'linear-gradient(to right, #6a82fb, #fc5c7d)';
                        break;
                    case 'rain':
                        weatherIcon.className = 'fas fa-cloud-rain text-xl mr-2';
                        weatherContainer.style.background = 'linear-gradient(to right, #00c6ff, #0072ff)';
                        break;
                    case 'thunderstorm':
                        weatherIcon.className = 'fas fa-bolt text-xl mr-2';
                        weatherContainer.style.background = 'linear-gradient(to right, #000000, #434343)';
                        break;
                    case 'snow':
                        weatherIcon.className = 'fas fa-snowflake text-xl mr-2';
                        weatherContainer.style.background = 'linear-gradient(to right, #E0EAFB, #C9D6FF)';
                        break;
                    default:
                        weatherIcon.className = 'fas fa-cloud text-xl mr-2';
                        weatherContainer.style.background = 'linear-gradient(to right, #d7d2cc, #304352)';
                }
            })
            .catch(error => {
                console.error('Error fetching weather data:', error);
                weatherText.textContent = 'Unable to fetch weather data';
                weatherContainer.style.background = 'linear-gradient(to right, #d7d2cc, #304352)';
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
                const defaultLat = -7.275665564;
                const defaultLon = 112.790163506;
                weatherText.textContent = 'Using default location';
                fetchWeather(defaultLat, defaultLon);
            }
        );
    } else {
        console.error('Geolocation is not supported by this browser.');
        weatherText.textContent = 'Geolocation not supported';
        const defaultLat = -7.275665564;
        const defaultLon = 112.790163506;
        fetchWeather(defaultLat, defaultLon);
    }
});
