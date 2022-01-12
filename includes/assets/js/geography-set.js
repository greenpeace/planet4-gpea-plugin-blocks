//wait for youtube api
var YTdeferred = jQuery.Deferred();
window.onYouTubeIframeAPIReady = function() {
   YTdeferred.resolve(window.YT);
};

jQuery(document).ready(function() {

  const $ = jQuery;
  const player = {};

  $.ajax({
    type: 'post',
    dataType: 'json',
    url: geography_set_data_object.ajaxUrl,
    data: {
      action: 'geography_get_ships',
      post_id: geography_set_data_object.postId,
    },
    success: function( data ){
      if( !data.success ) {
        return;
      }
      $.each(data.data, function(section_key, section) {
        $.each(section, function(ship_key, ship) {
          let $container = $('.section-geography-set:eq(' + section_key + ')');
          let $ship = $('.image__ship[data-ship="' + ship_key + '"]', $container);
          let $ship_lightbox = $('.section-geography-set__ship[data-section="' + $container.data('section') + '"][data-ship="' + ship_key + '"]');
          $ship.css({
            left: ship.x,
            top: ship.y,
          }).addClass('image__ship--loaded');
          $('.position--lat', $ship_lightbox).text(ship.lat);
          $('.position--long', $ship_lightbox).text(ship.long);
          randomOffsetTheShips();
        });
      });
    }
  });

  $('.section-geography-set [data-target="geography-set-video"]').on('click', function() {
    let $container = $(this).closest('.section-geography-set');
    let $target = $('.section-geography-set__video[data-section="' + $container.data('section') + '"]');
    closeAllLightboxes();
    $target.addClass('section-geography-set__lightbox--actived');
    $('body').addClass('has-open-section-geography-set-lightbox');
    playVideo($target, true);
  });

  $('.section-geography-set [data-target="geography-set-ship"]').on('click', function() {
    let $container = $(this).closest('.section-geography-set');
    let $target = $('.section-geography-set__ship[data-ship="' + $(this).data('ship') + '"][data-section="' + $container.data('section') + '"]');
    closeAllLightboxes();
    $('.image__ship--actived').removeClass('image__ship--actived');
    $target.addClass('section-geography-set__lightbox--actived');
    $('body').addClass('has-open-section-geography-set-lightbox');
    $(this).addClass('image__ship--actived');
    playVideo($target, true);
  });

  $('.section-geography-set__lightbox .lightbox__close-button').on('click', function() {
    closeAllLightboxes();
    $('.image__ship--actived').removeClass('image__ship--actived');
    $('body').removeClass('has-open-section-geography-set-lightbox');
  });

  $('.video-button--play').on('click', function() {
    playVideo($(this).closest('.section-geography-set__ship'));
  });

  $('.video-button--pause').on('click', function() {
    stopVideo($(this).closest('.section-geography-set__ship'));
  });

  $(window).resize(function() {
    randomOffsetTheShips();
  });

  function playVideo($target, runAutoPlay) {
    let $video = $('.video-content', $target);
    if(runAutoPlay && !$video.data('autoplay')) {
      return;
    }
    if($video.hasClass('video-content--file')) {
      let id = $video.attr('id');
      $video[0].currentTime = 0.1;
      $video[0].play();
      toggleStatusAttr(id, '1');
    }
    else if($video.hasClass('video-content--youtube')) {
      let id = $video.find('.youtube-embed').attr('id');
      player[ id ].seekTo(0);
      player[ id ].playVideo();
      toggleStatusAttr(id, '1');
    }
  }

  function stopVideo($target) {
    let $video = $('.video-content', $target);
    if($video.hasClass('video-content--file')) {
      let id = $video.attr('id');
      $video[0].pause();
      $video[0].currentTime = 0;
      toggleStatusAttr(id, '0');
    }
    else if($video.hasClass('video-content--youtube')) {
      let id = $video.find('.youtube-embed').attr('id');
      player[ id ].stopVideo();
      toggleStatusAttr(id, '0');
    }
  }

  function closeAllLightboxes() {
    let $target = $('.section-geography-set__lightbox--actived');
    $target.removeClass('section-geography-set__lightbox--actived');
    $target.each(function() {
      stopVideo($(this));
    });
  }

  function randomOffsetTheShips() {
    $('.section-geography-set').each(function() {
      let $container = $(this);
      $('.image__ship', $container).css({ transform: 'none' });
      $('.image__ship', $container).each(function() {
        let $this = $(this);
        $('.image__ship', $container).not($this).each(function() {
          if( Math.abs( $(this).offset().top - $this.offset().top ) < 25 &&
            Math.abs( $(this).offset().left - $this.offset().left ) < 25 ) {
            let yPosive = $(this).offset().top - $this.offset().top > 0 ? -1 : 1;
            let xPosive = $(this).offset().left - $this.offset().left > 0 ? -1 : 1;
            $(this).css({
              transform: 'translate(' + Math.floor(Math.random() * 30 * xPosive + 30 * xPosive) + 'px, ' +
                Math.floor(Math.random() * 30 * yPosive + 30 * yPosive) + 'px' + ')',
            });
          }
        });
      });
    });
  }

  function toggleStatusAttr(id, status) {
    $('#' + id).closest('.lightbox__column--video').attr('data-playing', status);
  }

  //init youtube
  YTdeferred.done(function(YT) {
      // use YT here
      $('.section-geography-set__lightbox [data-youtube-id]').each(function(){
          const id = $('.youtube-embed', this).attr('id');
          const isAutoplay = $(this).data('autoplay') || 0;
          const hasControls = $(this).data('controls') || 0;
          const ytId = $(this).data('youtube-id');
          player[id] = new YT.Player(id, {
              width: '100%',
              videoId: ytId,
              host: 'https://www.youtube-nocookie.com',
              playerVars: {
                  'modestbranding': 1,
                  'autohide': 1,
                  'showinfo': 0,
                  'loop': 1,
                  'mute': 1,
                  'controls': hasControls,
                  'rel': 0,
              },
              events: {
                  'onReady': function(){ },
                  'onStateChange': function onPlayerStateChange(event) {
                      if (event.data == YT.PlayerState.PLAYING) { 
                        toggleStatusAttr(id, '1');
                      }
                      if (event.data == YT.PlayerState.PAUSED) { 
                        toggleStatusAttr(id, '0');
                      }
                      if (event.data === YT.PlayerState.ENDED) {
                        player[id].playVideo();
                      }
                  }
              }
          });
      });
  });

});
