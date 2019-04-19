jQuery(document).ready(function() {
  var $ = jQuery;
  var containerID = 'engaging-campaign-buttons';
  var campaignButtonContainer = $('#' + containerID);
  var followingClass = 'engaging-campaign-following';
  var cookieExpireMs = 365 * 24 * 60 * 60 * 1000;

  campaignButtonContainer.on('pointerup', function(ev) {
    var followingIDs = [];

    $(ev.target).toggleClass(followingClass);

    campaignButtonContainer
      .find('.' + followingClass)
      .each(function() {
        followingIDs.push($(this).attr('id').replace(/^\D+/, ''));
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
