jQuery(document).ready(function() {
  var $ = jQuery;
  var containerID = 'section-choose-topics';
  var campaignButtonContainer = $('.' + containerID);
  var followingClass = 'active';
  var cookieExpireMs = 365 * 24 * 60 * 60 * 1000; // TODO decide cookie expiration date

  campaignButtonContainer.on('pointerup', function(ev) {
    var followingIDs = [];
    $(ev.target).toggleClass(followingClass);
    campaignButtonContainer
      .find('.' + followingClass)
      .each(function() {
        followingIDs.push($(this).attr('data'));
      });

    if(followingIDs.length) {
      var exp = new Date();
      var expTime = exp.getTime();
      expTime += cookieExpireMs;
      exp.setTime(expTime);
      document.cookie = 'engaging_following_IDs=' + followingIDs.join(',') + ';expires=' + exp + ';path=/';
    } else {
      document.cookie = 'engaging_following_IDs=;expires=' + new Date(0);
    }

  });
});
