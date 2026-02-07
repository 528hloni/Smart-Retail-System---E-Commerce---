const wheelVideos = [
    'landingpage/wheel1.mp4',
    'landingpage/wheel2.mp4',
    'landingpage/wheel3.mp4',
    'landingpage/wheel4.mp4',
    'landingpage/wheel5.mp4'
];

let currentVideoIndex = 0;

function changeVideo(direction) {
    currentVideoIndex += direction;
    

    if (currentVideoIndex >= wheelVideos.length) {
        currentVideoIndex = 0;
    } else if (currentVideoIndex < 0) {
        currentVideoIndex = wheelVideos.length - 1;
    }
    updateVideo();
}

function updateVideo() {
    const video = document.getElementById('carouselVideo');
    const source = document.getElementById('videoSource');
    
    
    source.src = wheelVideos[currentVideoIndex];
    video.load();
    video.play();
}

