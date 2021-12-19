//wait for youtube api
var YTdeferred = jQuery.Deferred();
window.onYouTubeIframeAPIReady = function() {
   YTdeferred.resolve(window.YT);
};

jQuery(document).ready(function() {

  const $ = jQuery;

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
          let $ship = $('.section-geography-set:eq(' + section_key + ') .image__ship[data-ship="' + ship_key + '"]');
          $ship.css({
            left: ship.x,
            top: ship.y,
          }).addClass('image__ship--loaded');
          console.log(ship.long);
          console.log(ship.lat);
          console.log(ship.x);
          console.log(ship.y);
        });
      });
    }
  });

});
