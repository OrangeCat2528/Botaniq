<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Botaniq Avatars</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="assets/fontawesome/css/all.css" rel="stylesheet">

    <style>
        body {
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-family: "Montserrat", sans-serif;
            font-optical-sizing: auto;
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-touch-callout: none;
            touch-action: manipulation;
            overflow-x: hidden;
        }
        #avatar-gif {
            pointer-events: none;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-user-drag: none;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        * {
            -webkit-tap-highlight-color: transparent;
        }
        html {
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

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

        .avatar-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100vw;
            height: 100vh;
            background-color: white;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            user-select: none;
        }
    </style>
</head>

<body class="mx-auto text-center scroll-smooth bg-gray-200">
    <div id="preloader">
        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="text-green-600 bi bi-arrow-repeat animate-spin" viewBox="0 0 16 16">
            <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z" />
            <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z" />
        </svg>
    </div>
    <div class="avatar-container">
        <img id="avatar-gif" src="" alt="Avatar GIF">
    </div>

    <script>
        const AVATAR_CONFIG = {
            gifs: {
                normal: ['good.gif', 'guitar.gif', 'watered.gif'],
                low: ['rontok.gif', 'crying.gif'],
                night: ['lollipop.gif']
            },
            thresholds: {
                soil: {
                    min: 60,
                    max: 70
                },
                humidity: {
                    min: 60,
                    max: 70
                },
                temp: {
                    min: 20
                }
            },
            timing: {
                updateInterval: 5000,
                preloaderDelay: 1000
            },
            nightHours: {
                start: 20.5,
                end: 4
            },
            apiUrl: 'https://botaniq.cogarden.app/backend/load_data.php'
        };

        class AvatarController {
            constructor() {
                this.avatarElement = document.getElementById('avatar-gif');
                this.previousCondition = null;
                this.updateTimer = null;
            }

            init() {
                this.setupEventListeners();
                this.startAvatarUpdater();
            }

            setupEventListeners() {
                window.addEventListener('load', async () => {
                    const preloader = document.getElementById('preloader');
                    try {
                        // Immediately fetch and display the correct avatar based on sensor data
                        await this.updateAvatar();
                    } catch (error) {
                        console.error('Initial avatar load failed:', error);
                        // Fallback to normal condition if initial load fails
                        this.avatarElement.src = `/assets/avatars/${this.getRandomGif('normal')}`;
                    } finally {
                        setTimeout(() => {
                            preloader.style.display = 'none';
                        }, AVATAR_CONFIG.timing.preloaderDelay);
                    }
                });
            }

            isNightMode() {
                const currentHour = new Date().getHours();
                return currentHour >= AVATAR_CONFIG.nightHours.start || currentHour < AVATAR_CONFIG.nightHours.end;
            }

            getRandomGif(condition) {
                const gifs = AVATAR_CONFIG.gifs[condition];
                return gifs[Math.floor(Math.random() * gifs.length)];
            }

            async fetchSensorData() {
                try {
                    const response = await fetch(AVATAR_CONFIG.apiUrl);
                    if (!response.ok) throw new Error('Network response was not ok');

                    const data = await response.json();
                    return {
                        temp: parseFloat(data.temp),
                        humidity: parseFloat(data.humidity),
                        soil: parseFloat(data.soil)
                    };
                } catch (error) {
                    console.error('Error fetching sensor data:', error);
                    return null;
                }
            }

            evaluateConditions(data) {
                if (!data) return 'normal'; // Default to normal if no data

                const {
                    soil,
                    humidity,
                    temp
                } = data;
                const thresholds = AVATAR_CONFIG.thresholds;

                const isMoistureLow = soil < thresholds.soil.min || soil > thresholds.soil.max;
                const isHumidityLow = humidity < thresholds.humidity.min || humidity > thresholds.humidity.max;
                const isTempLow = temp < thresholds.temp.min;

                const isLowCondition = isMoistureLow || isHumidityLow || isTempLow;

                if (this.isNightMode()) {
                    return isLowCondition ? 'low' : 'night';
                }
                return isLowCondition ? 'low' : 'normal';
            }

            async updateAvatar() {
                try {
                    const sensorData = await this.fetchSensorData();
                    const currentCondition = this.evaluateConditions(sensorData);

                    // Update avatar if it's the first load or condition has changed
                    if (this.previousCondition === null || currentCondition !== this.previousCondition) {
                        const newGif = this.getRandomGif(currentCondition);
                        this.avatarElement.src = `/assets/avatars/${newGif}`;
                        this.previousCondition = currentCondition;
                    }

                    return currentCondition; // Return the condition for potential use
                } catch (error) {
                    console.error('Error updating avatar:', error);
                    throw error;
                }
            }

            startAvatarUpdater() {
                // Clear any existing timer
                if (this.updateTimer) {
                    clearInterval(this.updateTimer);
                }

                // Start new update cycle
                this.updateTimer = setInterval(
                    () => this.updateAvatar(),
                    AVATAR_CONFIG.timing.updateInterval
                );
            }

            destroy() {
                if (this.updateTimer) {
                    clearInterval(this.updateTimer);
                }
            }
        }

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            const avatarController = new AvatarController();
            avatarController.init();
        });
    </script>
</body>

</html>