//wait for youtube api
var YTdeferred = jQuery.Deferred();
window.onYouTubeIframeAPIReady = function() {
   YTdeferred.resolve(window.YT);
};

jQuery(document).ready(function() {
    var $ = jQuery;
    var player = {};
    const classIsPlaying = 'is-playing';
    const classIsAutoPlay = 'is-auto-play';
    const classIsStopped = 'is-stopped';
    const srcVideo = 'video';
    const srcYoutube = 'youtube';

    function bindButton(){
        $('.video-row-play').click(function(e){
            const parent = $(this).parents('.gpea-video-row-wrapper-full');
            const id = parent.attr('data-id');
            const src = parent.attr('src-type');
            parent.removeClass(classIsStopped).removeClass(classIsAutoPlay);
            parent.addClass(classIsPlaying);
            switch(src){
                case srcVideo: 
                    var video = document.getElementById(id);
                    video.muted = false;
                    video.currentTime = 0.1;
                    video.play();
                    break;
                case srcYoutube:
                    player[id].unMute();
                    player[id].seekTo(0);
                    player[id].playVideo();
                    break;
            }
        });
        $('.video-row-stop').click(function(e){
            const parent = $(this).parents('.gpea-video-row-wrapper-full');
            const id = parent.attr('data-id');
            const src = parent.attr('src-type');
            parent.removeClass(classIsPlaying).removeClass(classIsAutoPlay);
            parent.addClass(classIsStopped);
            switch(src){
                case srcVideo: 
                    document.getElementById(id).pause();
                    break;
                case srcYoutube:
                    player[id].pauseVideo();
                    break;
            }
        });
    }
    //init youtube
    YTdeferred.done(function(YT) {
        // use YT here
        jQuery('.video-row-youtube').each(function(idx, el){
            const id = jQuery(this).attr('id');
            const isAutoplay = jQuery(this)
                                    .parent('.gpea-video-row-wrapper-full')
                                    .attr('is-autoplay') || 0;
            player[id] = new YT.Player(id, {
                width: '100%',
                videoId: 'a3ICNMQW7Ok',
                host: 'https://www.youtube-nocookie.com',
                playerVars: {
                    'autoplay': isAutoplay,
                    'modestbranding': 1,
                    'autohide': 1,
                    'showinfo': 0,
                    'loop': 1,
                    'mute': 1,
                    'controls': 0,
                    'rel': 0,
                },
                events: {
                    'onReady': function(){ },
                    'onStateChange': function onPlayerStateChange(event) {
                        if (event.data == YT.PlayerState.PLAYING) { }
                        if (event.data === YT.PlayerState.ENDED) {
                            player[id].playVideo();
                        }
                    }
                }
            });
        });
    });

    bindButton();
});
  