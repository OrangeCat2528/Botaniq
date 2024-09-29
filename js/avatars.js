let previousData = {
    temp: null,
    humidity: null,
    soil: null,
};
let declineCount = 0;
let currentMode = 'normal';

function getCurrentTime() {
    const now = new Date();
    const hours = now.getHours();
    return hours;
}

function isNightMode() {
    const hours = getCurrentTime();
    return (hours >= 20.5 || hours < 4);
}

function updateAvatar(videoSrc) {
    const avatarDiv = document.querySelector('.m-5.p-5.bg-white.rounded-3xl.shadow-lg');
    avatarDiv.innerHTML = `
        <video autoplay loop muted class="w-full rounded-3xl">
            <source src="/assets/avatars/${videoSrc}" type="video/mp4">
            Device tidak support video tag.
        </video>
    `;
}

function selectRandomVideo(videos) {
    const randomIndex = Math.floor(Math.random() * videos.length);
    return videos[randomIndex];
}

function loadAvatars(temp, humidity, soil) {
    const isMoistureLow = soil < 58 || soil > 61;
    const isHumidityLow = humidity < 58 || humidity > 61;
    const isTempLow = temp < 20;

    const normalVideos = ['good.mp4', 'guitar.mp4', 'watered.mp4'];
    const lowMoistureVideos = ['rontok.mp4', 'crying.mp4'];
    const nightModeVideos = ['lollipop.mp4'];

    if (isNightMode()) {
        if (isMoistureLow || isHumidityLow || isTempLow) {
            currentMode = 'moistureLess';
            updateAvatar(selectRandomVideo(lowMoistureVideos));
        } else {
            currentMode = 'night';
            updateAvatar(selectRandomVideo(nightModeVideos));
        }
    } else {
        if (isMoistureLow || isHumidityLow || isTempLow) {
            currentMode = 'moistureLess';
            updateAvatar(selectRandomVideo(lowMoistureVideos));
        } else {
            currentMode = 'normal';
            updateAvatar(selectRandomVideo(normalVideos));
        }
    }
}

function monitorData(data) {
    const { temp, humidity, soil } = data;

    if (
        previousData.temp !== null &&
        (temp < previousData.temp || humidity < previousData.humidity || soil < previousData.soil)
    ) {
        declineCount++;
    } else {
        declineCount = 0;
    }

    if (declineCount >= 5) {
        loadAvatars(temp, humidity, soil);
        declineCount = 0;
    }

    previousData = { temp, humidity, soil };
}

function load_data() {
    fetch('https://botaniq.cogarden.app/backend/load_data.php')
        .then(response => response.json())
        .then(data => {
            monitorData({
                temp: parseFloat(data.temp),
                humidity: parseFloat(data.humidity),
                soil: parseFloat(data.soil)
            });
        })
        .catch(error => console.error('Error loading data:', error));
}

document.addEventListener('DOMContentLoaded', () => {
    setInterval(load_data, 500);
});
