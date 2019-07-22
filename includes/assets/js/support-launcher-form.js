jQuery(document).ready(function() {
  $('.form-support-launcher').on('submit', function(event) {
    event.preventDefault();
    event.stopPropagation();

    var actionName = 'supportLauncher';
    var $this = jQuery(this);
    var messageBox = $(this).find('.form-support-launcher-message');
    var destination = $this.find('input[name=send_to]').val();

    var data = $this.serializeArray(),
        payload = {
          action: actionName,
        };

    for( var i = 0, len = data.length; i < len; i++ ) {
      if(data[i].name !== actionName) {
        payload[data[i].name] = data[i].value;
      }
    }

    if( payload && destination ) {
      jQuery.ajax(
        destination,
        {
          data: payload,
          method: 'POST',
        }
      ).done(function(response) {
        // messageBox.html(response);
        messageBox.show();
      });
    }

    return false;

  });
});
