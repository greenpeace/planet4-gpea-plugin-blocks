$(document).ready(function() {

  // Hide the consentbox if the opt=in url var is set. (this is for set for ie mailings)
  var opt=getUrlVars()['opt'];
  var url_cg = getUrlVars()['cg'];
  var isfacebook = document.referrer.indexOf('facebook') !== -1;
  var istwitter = document.referrer.indexOf('twitter') !== -1;

  let clangct=getUrlVars()['clangct'];

  if(clangct != undefined){
    $.ajax({
      url: '/wp-content/plugins/planet4-gpnl-plugin-blocks/includes/assets/js/clang-landing.js?clangct='+clangct,
      dataType: 'script',
    });
  }

  if(opt!= undefined && $('.optin').length != 0 && opt=='in'){
    $('.optin').hide();
    $('.gpnl-petition-checkbox').prop( 'checked', true );

    // Here we check if we know the mail being entered if the opt=in var is set.
    // If we don't know the entered mail we should display the consentbox
    $( 'input[name=\'mail\']' ).keyup(function() {
      // First loosely check if the value in the mailinput is indeed a mailadress, if it indeed is, we pass it onto the database checker
      // eslint-disable-next-line
      var mailRegex = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
      if (mailRegex.test(this.value)) {
        let mail = encodeURIComponent(this.value);
        $.ajax({
          type: 'GET',
          url: 'https://secure.greenpeacephp.nl/kenikdeze.php?mail=' + mail,
          complete: function(data) {
            // If we do not know the email, we display the consentbox again
            if (data.responseText.includes('false')) {
              $('.optin').show();
              $('.gpnl-petition-checkbox').prop( 'checked', false );
            }
          }
        });
      }
    });

    if ( !(istwitter || isfacebook)   ){
      prefillByGuid('prefill', this);
    }
  }

  $('.gpnl-petitionform').each(function(){
    var post_form_value = getFormObj(this);
    var form_config = 'petition_form_object_' + post_form_value['form_id'];
    this.tellerCode = window[form_config].analytics_campaign;
    this.counter_min = Number(window[form_config].countermin);
    this.counter_max = Number(window[form_config].countermax);
    this.counter_text = window[form_config].countertext;

    prefillByGuid('teller', this);
  });

  function prefillByGuid(type, form){
    var xmlhttp = new XMLHttpRequest();
    var query_id = '';
    var requestValue = '';
    // waar gaat het om? Een teller of een prefill?
    if (type === 'prefill'){
      query_id = 'GET_FIRST_NAME_EMAIL';
      requestValue = url_cg;
    } else if (type === 'teller'){
      query_id = 'CAMP_TTL_PETITIONS';
      requestValue = form.tellerCode;
    }
    xmlhttp.open('POST', 'https://www.mygreenpeace.nl/GPN.WebServices/WIDSService.asmx', true);
    // build SOAP request
    var sr = '<'+'?'+'xml version="1.0" encoding="utf-8"?>' +
    '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">' +
    '  <soap:Body>' +
    '    <WatIsDestand xmlns="http://www.mygreenpeace.nl/GPN.WebServices/">' +
    '      <queryId>'+query_id+'</queryId>' +
    '      <requestValue>'+requestValue+'</requestValue>' +
    '    </WatIsDestand>' +
    '  </soap:Body>' +
    '</soap:Envelope>';

    xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState === 4 && xmlhttp.status === 200){ // 200 = OK
        var response = xmlhttp.responseXML.getElementsByTagName('WatIsDestandResult')[0].firstChild.nodeValue;
        if (response!==''){
          var res = response.split('|');
          // waar gaat het om? Een teller of een prefill
          if (type === 'prefill'){
            var naam = res[0];
            $(form).find('input[name=\'name\']').val(naam);
            var email = res[1];
            $(form).find('input[name=\'mail\']').val(email);
          } else if (type === 'teller'){
            showCounter(Number(res[0]), form);
          }
        }
      }
    };
    // Send the POST request
    xmlhttp.setRequestHeader('Content-Type', 'text/xml');
    xmlhttp.setRequestHeader('SOAPAction', 'http://www.mygreenpeace.nl/GPN.WebServices/WatIsDestand');
    xmlhttp.send(sr);
    // send request
  }

  // TODO add language preference detection for better formatting of numbers
  function showCounter(num_responses, form){
    let formwrapper = form.parentNode;
    if (num_responses >= form.counter_min){
      $(formwrapper).find('.counter').show();
      var perc_slider = Math.round(100 *(num_responses / form.counter_max));

      if (num_responses >= form.counter_max) {
        perc_slider = 100;
      }

      $(formwrapper).find('.counter__slider').animate({width: perc_slider+'%', opacity: 1}, 2000, 'easeInOutCubic');
      $(formwrapper).find('.counter__gettext').html(num_responses.toLocaleString('nl-NL') +' '+form.counter_text);
      $(formwrapper).find('.counter__text').fadeIn(2000);
    }
  }

  //  try to get an response from whatsapp, else hide the whatsappbutton
  //  ATM not working because ajax doesn't support custom schemes...
  // TODO Find different way of determining whatsapp support
  $.ajax({
    type: 'HEAD',
    url: 'whatsapp://send?text=text=Hello%20World!',
    success: function() {
      // window.location='whatsapp://send?text=text=Hello%20World!';
    },
    error: function() {
      $('.gpnl-share-whatsapp').toggle();
    }
  });
});

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

// Get the key+value from the input fields in the form
function getFormObj(el) {
  var formObj = {};
  var inputs = $(el).serializeArray();
  $.each(inputs, function (i, input) {
    formObj[input.name] = input.value;
  });
  return formObj;
}
