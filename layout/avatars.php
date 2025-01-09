<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
?>

<!DOCTYPE html>
<html lang="en">
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

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(-25%);
                animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
            }
            50% {
                transform: translateY(0);
                animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        .animate-bounce {
            animation: bounce 1s infinite;
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
    <!-- Improved Preloader -->
    <div id="preloader" class="fixed inset-0 bg-white z-50 flex items-center justify-center transition-opacity duration-500">
        <div class="relative">
            <div class="w-16 h-16 border-4 border-green-200 rounded-full"></div>
            <div class="w-16 h-16 border-4 border-green-500 rounded-full animate-spin absolute top-0 left-0 border-t-transparent"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                <i class="fas fa-leaf text-green-500 animate-bounce"></i>
            </div>
        </div>
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
                this.isDemoMode = false;
            }

            init() {
                this.setupEventListeners();
                this.startAvatarUpdater();
            }

            getRandomAvatar() {
                const allAvatars = [
                    ...AVATAR_CONFIG.gifs.normal,
                    ...AVATAR_CONFIG.gifs.low,
                    ...AVATAR_CONFIG.gifs.night
                ];
                return allAvatars[Math.floor(Math.random() * allAvatars.length)];
            }

            setupEventListeners() {
                window.addEventListener('load', async () => {
                    const preloader = document.getElementById('preloader');
                    try {
                        await this.updateAvatar();
                    } catch (error) {
                        console.error('Initial avatar load failed:', error);
                        this.avatarElement.src = `/assets/avatars/${this.getRandomAvatar()}`;
                    } finally {
                        setTimeout(() => {
                            preloader.style.opacity = '0';
                            setTimeout(() => {
                                preloader.style.display = 'none';
                            }, 500);
                        }, AVATAR_CONFIG.timing.preloaderDelay);
                    }
                });

                // Change avatar when tab becomes visible (for demo mode)
                document.addEventListener('visibilitychange', () => {
                    if (this.isDemoMode && document.visibilityState === 'visible') {
                        this.updateAvatar();
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
                    
                    // Check if it's demo mode
                    if (data.product_id === "DEMO") {
                        this.isDemoMode = true;
                        return {
                            temp: Math.random() * (30 - 20) + 20,
                            humidity: Math.random() * (80 - 60) + 60,
                            soil: Math.random() * (70 - 60) + 60,
                            isDemo: true
                        };
                    }

                    return {
                        temp: parseFloat(data.temp),
                        humidity: parseFloat(data.humidity),
                        soil: parseFloat(data.soil),
                        isDemo: false
                    };
                } catch (error) {
                    console.error('Error fetching sensor data:', error);
                    return null;
                }
            }

            evaluateConditions(data) {
                if (!data) return 'normal';

                const { soil, humidity, temp } = data;
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

                    if (sensorData?.isDemo) {
                        const newGif = this.getRandomAvatar();
                        this.avatarElement.src = `/assets/avatars/${newGif}`;
                        return 'demo';
                    }

                    const currentCondition = this.evaluateConditions(sensorData);
                    if (this.previousCondition === null || currentCondition !== this.previousCondition) {
                        const newGif = this.getRandomGif(currentCondition);
                        this.avatarElement.src = `/assets/avatars/${newGif}`;
                        this.previousCondition = currentCondition;
                    }

                    return currentCondition;
                } catch (error) {
                    console.error('Error updating avatar:', error);
                    throw error;
                }
            }

            startAvatarUpdater() {
                if (this.updateTimer) {
                    clearInterval(this.updateTimer);
                }

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