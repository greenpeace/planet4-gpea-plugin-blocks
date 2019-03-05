$('.gpnl-petitionform').on('submit', function () {
  var petition_form_element = this;
  // Get the  parameter from the petition form and add the action and CSRF protection
  var post_form_value = getFormObj(petition_form_element);
  var form_config = 'petition_form_object_' + post_form_value['form_id'];
    
  post_form_value.action = 'petition_form_process';
  post_form_value.nonce  = window[form_config].nonce;
  post_form_value.ad_campaign = window[form_config].ad_campaign;

  // Disable the form so people can't resubmit
  toggleDisable($(petition_form_element).find('*'));

  // Do a ajax call to the wp_admin admin_ajax.php,
  // which triggers processing function in the petition block
  $.ajax({
    type:    'POST',
    url:     window[form_config].ajaxUrl,
    data:    post_form_value,
    success: function(data) {
      console.log('^-^');

      // Send conversion event to the GTM
      if (typeof dataLayer !== 'undefined') {
        dataLayer.push({
          'event'         :'petitiebutton',
          'conv_campaign' : window[form_config].analytics_campaign,
          'conv_action'   : window[form_config].ga_action,
          'conv_label'    :'registreer'
        });
      }

      // if the consent was ticked or consent was given by entering phonenumber
      if (post_form_value.consent === 'on' || post_form_value.phone !== '') {
        // If an ad campaign is run by an external company fire the conversiontracking
        if (window[form_config].ad_campaign === 'SB') {
          fbq('track', 'Lead');
          // if it is run by social blue, also deduplicate
          socialBlueDeDuplicate(post_form_value['mail'], data['data']['phone'], window[form_config].apref);
        } else if (window[form_config].ad_campaign === 'JA') {
          fbq('track', window[form_config].jalt_track);
        }
      }

      if (post_form_value.phone !== ''){
        // Send conversion event to the GTM
        if (typeof dataLayer !== 'undefined') {
          dataLayer.push({
            'event'         :'petitiebutton',
            'conv_campaign' : window[form_config].analytics_campaign,
            'conv_action'   :'telnr',
            'conv_label'    :'Ja'
          });
        }
      }
      else{
        if (typeof dataLayer !== 'undefined') {
          dataLayer.push({
            'event'         :'petitiebutton',
            'conv_campaign' : window[form_config].analytics_campaign,
            'conv_action'   :'telnr',
            'conv_label'    :'Nee'
          });
        }
      }

      // cardflip the card, positionattribute flips to make sure no problems arises with different lengths of the front and back of the card, finally hide the front
      cardflip(petition_form_element);

      let clangct=getUrlVars()['clangct'];
      if(clangct != undefined){
        $.ajax({
          url: '/wp-content/plugins/planet4-gpnl-plugin-blocks/includes/assets/js/clang-conversion.js?clangct='+clangct,
          dataType: 'script',
        });
      }
    },
    error: function(jqXHR, textStatus, errorThrown){
      // If the backend sends an error, hide the thank element and show an error urging to try again
      console.log('o_o');
      console.log('ERRORS: ' + textStatus + ': ' + errorThrown);
      var cardback = $(petition_form_element.parentNode.nextElementSibling);
      cardback.find('*').hide('');
      cardback.append('<p>Sorry, er gaat momenteel iets fout, probeer het nu of later opnieuw.</p>');
      cardback.append(
        '<a href=\''+window.location.href +'\' class="btn btn-primary btn-block"' +
        '">Probeer opnieuw</a>');
      cardflip(petition_form_element);
    }
  });
});

// Get the key+value from the input fields in the form
function getFormObj(el) {
  var formObj = {};
  var inputs = $(el).serializeArray();
  $.each(inputs, function (i, input) {
    formObj[input.name] = input.value;
  });
  return formObj;
}

// Toggle the disabled state on form elements
function toggleDisable(el) {
  el.prop('disabled', !el.prop('disabled'));

}

// toggle the flipped class for the card parent
function cardflip(el) {
  let element = $(el);
  let parent =  el.parentNode;
  let card = $(el.parentNode.parentNode);

  // first hide the signing button
  $(element.find('.signBtn')).toggle();
  $(element.find('.policies')).toggle();
  // then cardflip the position attribute on the front and back of the card to support different lengths front and back
  $(parent.nextElementSibling).css( 'position', flip_positionattribute(parent.nextElementSibling));
  $(parent).css( 'position', flip_positionattribute(parent));
  // then cardflip the card
  card.toggleClass('flipped');

  // after animation hide the front
  card.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend',
    function() {
      $(parent).toggle();
      card.off('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend');
    });

}

function flip_positionattribute (el){
  return $(el).css('position') === 'absolute' ? 'relative' : 'absolute';
}

/* eslint-disable */
function fireShareEvent (platform){
  if (typeof dataLayer !== 'undefined') {
    dataLayer.push({
      'event'         :'petitiebutton',
      'conv_campaign' :window[form_config].analytics_campaign,
      'conv_action'   :window[form_config].ga_action,
      'conv_label'    :'share_' + platform});
  }
  else{
    console.log('GTM not defined?');
  }
}
/* eslint-enable */

/* eslint-disable */
// Send the supporter data to for deduplication
function socialBlueDeDuplicate(email, phone, apref) {
  var apHost = ('https:' == document.location.protocol ? 'https://secure.event.' : 'http://www.') + 'affiliatepartners.com';
  var apSrc = '/js/ApConversionPixelV1.1.js';

  _apOrderValue = 0;
  _apOrderNumber = 'email=' + email + '-telefoonnumer=' + phone;
  _apRef = apref;

  try {
    $('body').append(unescape('%3Cscript src=\'' + apHost + apSrc + '\' type=\'text/javascript\'%3E%3C/script%3E'));
  } catch (err) {
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = apHost + apSrc;
    document.getElementsByTagName('head')[0].appendChild(script);
  }
}
/* eslint-enable */

// REFACTOR IE11 doesn't support UrlSearchParams, so custom UrlParam function.
// 	Consider polyfilling it now? or wait until we drop IE11 support and switch then?
function getUrlVars(){
  var vars = [],
    hash;
  var uri = window.location.href.split('#')[0];
  var hashes = uri.slice(window.location.href.indexOf('?') + 1).split('&');
  for(var i = 0; i < hashes.length; i++){
    hash = hashes[i].split('=');
    vars.push(hash[0]);
    vars[hash[0]] = hash[1];
  }
  return vars;
}
