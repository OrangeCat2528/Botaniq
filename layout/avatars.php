<?php
// Enable CORS by allowing any origin (or restrict to specific domains if needed)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Botaniq Avatars</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="assets/fontawesome/css/all.css" rel="stylesheet"> <!-- Font Awesome Icons -->

    <style>
        /* Preloader spinner animation */
        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        /* Hide the preloader once the page is loaded */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: white;
            z-index: 50;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Fullscreen avatar container */
        .avatar-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100vw;
            height: 100vh;
            background-color: white;
        }

        /* Ensuring the GIF maintains its aspect ratio and is centered */
        img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
    </style>
</head>

<body class="mx-auto text-center scroll-smooth bg-gray-200">

    <!-- Preloader HTML -->
    <div id="preloader">
        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="text-green-600 bi bi-arrow-repeat animate-spin" viewBox="0 0 16 16">
            <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
            <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
        </svg>
    </div>

    <!-- Avatar GIF Container -->
    <div class="avatar-container">
        <img id="avatar-gif" src="" alt="Avatar GIF">
    </div>

    <script>
        const normalGifs = ['good.gif', 'guitar.gif', 'watered.gif'];
        const lowMoistureGifs = ['rontok.gif', 'crying.gif'];
        const nightModeGifs = ['lollipop.gif'];

        const avatarImage = document.getElementById('avatar-gif');

        let previousCondition = 'normal';  // Track previous condition

        function getCurrentTime() {
            const now = new Date();
            return now.getHours();
        }

        function isNightMode() {
            const hours = getCurrentTime();
            return (hours >= 20.5 || hours < 4);
        }

        function selectRandomGif(gifs) {
            const randomIndex = Math.floor(Math.random() * gifs.length);
            return gifs[randomIndex];
        }

        function loadAvatar(temp, humidity, soil) {
            const isMoistureLow = soil < 60 || soil > 70;  // Adjusted the range for better control
            const isHumidityLow = humidity < 60 || humidity > 70;  // Adjusted range
            const isTempLow = temp < 20;

            let selectedGif;
            let currentCondition;

            // Determine current condition
            if (isNightMode()) {
                currentCondition = isMoistureLow || isHumidityLow || isTempLow ? 'low' : 'night';
            } else {
                currentCondition = isMoistureLow || isHumidityLow || isTempLow ? 'low' : 'normal';
            }

            // Only update the avatar if the condition changes
            if (currentCondition !== previousCondition) {
                if (currentCondition === 'night') {
                    selectedGif = selectRandomGif(nightModeGifs);
                } else if (currentCondition === 'low') {
                    selectedGif = selectRandomGif(lowMoistureGifs);
                } else {
                    selectedGif = selectRandomGif(normalGifs);
                }

                avatarImage.src = `/assets/avatars/${selectedGif}`;
                previousCondition = currentCondition;  // Update the previous condition
            }
        }

        function fetchData() {
            return fetch('https://botaniq.cogarden.app/backend/load_data.php')
                .then(response => response.json())
                .then(data => {
                    return {
                        temp: parseFloat(data.temp),
                        humidity: parseFloat(data.humidity),
                        soil: parseFloat(data.soil)
                    };
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        function startAvatarUpdater() {
            setInterval(async () => {
                const data = await fetchData();
                loadAvatar(data.temp, data.humidity, data.soil);
            }, 5000); // Update every 5 seconds
        }

        // Hide preloader after all gifs are loaded with 2 second delay
        window.addEventListener('load', function () {
            const preloader = document.getElementById('preloader');

            setTimeout(function() {
                // Randomize avatar on initial load
                avatarImage.src = `/assets/avatars/${selectRandomGif(normalGifs)}`;
                
                // Hide the preloader
                preloader.style.display = 'none';
            }, 1000);  // 2 second delay for spinner
        });

        document.addEventListener('DOMContentLoaded', () => {
            startAvatarUpdater();
        });
    </script>
</body>
</html>
