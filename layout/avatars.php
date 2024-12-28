<?php
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
    <link href="assets/fontawesome/css/all.css" rel="stylesheet">

    <style>
        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        #preloader {
            position: fixed;
            inset: 0;
            background-color: white;
            z-index: 50;
            display: grid;
            place-items: center;
        }

        .avatar-container {
            display: grid;
            place-items: center;
            width: 100vw;
            height: 100vh;
            background-color: white;
        }

        .avatar-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
    </style>
</head>

<body class="mx-auto text-center scroll-smooth bg-gray-200">
    <div id="preloader">
        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="text-green-600 bi bi-arrow-repeat animate-spin" viewBox="0 0 16 16">
            <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
            <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
        </svg>
    </div>
    <div class="avatar-container">
        <img id="avatar-gif" class="avatar-image" src="" alt="Avatar GIF">
    </div>

    <script>
        const GIFS = {
            normal: ['good.gif', 'guitar.gif', 'watered.gif'],
            low: ['rontok.gif', 'crying.gif'],
            night: ['lollipop.gif']
        };

        const THRESHOLDS = {
            soil: { min: 60, max: 70 },
            humidity: { min: 60, max: 70 },
            temp: { min: 20 }
        };

        const UPDATE_INTERVAL = 5000;
        const PRELOADER_DELAY = 1000;
        const NIGHT_HOURS = { start: 20.5, end: 4 };
        const API_URL = 'https://botaniq.cogarden.app/backend/load_data.php';

        class AvatarManager {
            constructor() {
                this.avatarElement = document.getElementById('avatar-gif');
                this.previousCondition = 'normal';
                this.init();
            }

            init() {
                this.setupEventListeners();
                this.startAvatarUpdater();
            }

            setupEventListeners() {
                window.addEventListener('load', () => this.handleInitialLoad());
            }

            handleInitialLoad() {
                const preloader = document.getElementById('preloader');
                setTimeout(() => {
                    this.updateAvatarSrc(this.getRandomGif('normal'));
                    preloader.style.display = 'none';
                }, PRELOADER_DELAY);
            }

            isNightMode() {
                const hours = new Date().getHours();
                return hours >= NIGHT_HOURS.start || hours < NIGHT_HOURS.end;
            }

            getRandomGif(condition) {
                const gifs = GIFS[condition];
                return gifs[Math.floor(Math.random() * gifs.length)];
            }

            updateAvatarSrc(gif) {
                this.avatarElement.src = `/assets/avatars/${gif}`;
            }

            async fetchData() {
                try {
                    const response = await fetch(API_URL);
                    const data = await response.json();
                    return {
                        temp: parseFloat(data.temp),
                        humidity: parseFloat(data.humidity),
                        soil: parseFloat(data.soil)
                    };
                } catch (error) {
                    console.error('Error fetching data:', error);
                    return null;
                }
            }

            determineCondition(data) {
                if (!data) return this.previousCondition;

                const isMoistureLow = data.soil < THRESHOLDS.soil.min || data.soil > THRESHOLDS.soil.max;
                const isHumidityLow = data.humidity < THRESHOLDS.humidity.min || data.humidity > THRESHOLDS.humidity.max;
                const isTempLow = data.temp < THRESHOLDS.temp.min;

                const isLowCondition = isMoistureLow || isHumidityLow || isTempLow;
                
                if (this.isNightMode()) {
                    return isLowCondition ? 'low' : 'night';
                }
                return isLowCondition ? 'low' : 'normal';
            }

            async updateAvatar() {
                const data = await this.fetchData();
                const currentCondition = this.determineCondition(data);

                if (currentCondition !== this.previousCondition) {
                    this.updateAvatarSrc(this.getRandomGif(currentCondition));
                    this.previousCondition = currentCondition;
                }
            }

            startAvatarUpdater() {
                setInterval(() => this.updateAvatar(), UPDATE_INTERVAL);
            }
        }
        document.addEventListener('DOMContentLoaded', () => new AvatarManager());
    </script>
</body>
</html>