//wait for youtube api
var YTdeferred = jQuery.Deferred();
window.onYouTubeIframeAPIReady = function() {
   YTdeferred.resolve(window.YT);
};

jQuery(document).ready(function() {
    console.log('video row', YT);
    var player = {};
    YTdeferred.done(function(YT) {
        // use YT here
        console.log(YT);
        jQuery('.video-row-youtube').each(function(idx, el){
            console.log(el);
            const id = jQuery(this).attr('id');
            player[id] = new YT.Player(id, {
                width: '100%',
                videoId: '668nUCeBHyY',
                host: 'https://www.youtube-nocookie.com',
                playerVars: {
                    'autoplay': 1,
                    'modestbranding': 1,
                    'autohide': 1,
                    'showinfo': 0,
                    'loop': 1,
                    'mute': 1,
                    'controls': 0,
                },
                events: {
                    'onReady': function(){ },
                    'onStateChange': function onPlayerStateChange(event) {
                        if (event.data == YT.PlayerState.PLAYING && !done) { }
                        if (event.data === YT.PlayerState.ENDED) {
                            player[id].playVideo();
                        }
                    }
                }
            });
        });
    });
});
  