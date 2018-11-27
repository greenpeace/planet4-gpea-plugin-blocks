$(document).ready(function() {

  // Hide the consentbox if the opt=in url var is set. (this is for set for ie mailings)
  var opt=getUrlVars()['opt'];
  if(opt!= undefined && $('.optin').length != 0 && opt=='in'){
    $('.optin').hide();
    $('.gpnl-petition-checkbox').prop( "checked", true );

    // Here we check if we know the mail being entered if the opt=in var is set.
    // If we don't know the entered mail we should display the consentbox
    $('#mail').keyup(function(event) {
      // First loosely check if the value in the mailinput is indeed a mailadress, if it indeed is, we pass it onto the database checker
      var mailRegex = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
      if (mailRegex.test(this.value)) {
        mail = encodeURIComponent(this.value),
        $.ajax({
          type: 'GET',
          url: "https://secure.greenpeacephp.nl/kenikdeze.php?mail=" + mail,
          complete: function(data) {
            // If we do not know the email, we display the consentbox again
            if (data.responseText.includes('false')) {
              $('.optin').show();
              $('.gpnl-petition-checkbox').prop( "checked", false ); 
            }
          }
        });
      }
    });
  }

  var tellerCode = petition_form_object.analytics_campaign;
  var counter_min = 1000;
  var counter_max = petition_form_object.countermax;
  var url_cg = getUrlVars()["cg"];
  var isfacebook = document.referrer.indexOf('facebook') !== -1;
  var istwitter = document.referrer.indexOf('twitter') !== -1;

	if ( !(istwitter || isfacebook)   ){
		prefillByGuid('prefill');
	}

  prefillByGuid('teller');

  function prefillByGuid(type){
    var xmlhttp = new XMLHttpRequest();
    var query_id = '';
    var requestValue = '';
    // waar gaat het om? Een teller of een prefill?
	  if (type === 'prefill'){
		  query_id = 'GET_FIRST_NAME_EMAIL';
		  requestValue = url_cg;
	  } else if (type === 'teller'){
      query_id = 'CAMP_TTL_PETITIONS';
      requestValue = tellerCode;//'CABO1-2016';
    }
    xmlhttp.open("POST", "https://www.mygreenpeace.nl/GPN.WebServices/WIDSService.asmx", true);
    // build SOAP request
    var sr = "<"+"?"+"xml version=\"1.0\" encoding=\"utf-8\"?>" +
    "<soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\">" +
    "  <soap:Body>" +
    "    <WatIsDestand xmlns=\"http://www.mygreenpeace.nl/GPN.WebServices/\">" +
    "      <queryId>"+query_id+"</queryId>" +
    "      <requestValue>"+requestValue+"</requestValue>" +
    "    </WatIsDestand>" +
    "  </soap:Body>" +
    "</soap:Envelope>";

    xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState === 4 && xmlhttp.status === 200){ // 200 = OK
			response = xmlhttp.responseXML.getElementsByTagName("WatIsDestandResult")[0].firstChild.nodeValue;
			if (response!==""){
				var res = response.split("|");
				// waar gaat het om? Een teller of een prefill
				if (type === 'prefill'){
					var naam = res[0];
					$('#name').val(naam);
					var email = res[1];
					$('#mail').val(email);
				} else if (type === 'teller'){
					toonTeller(res[0]);
				}
			}
		}
	};
	  // Send the POST request
	  xmlhttp.setRequestHeader("Content-Type", "text/xml");
    xmlhttp.setRequestHeader("SOAPAction", "http://www.mygreenpeace.nl/GPN.WebServices/WatIsDestand");
    xmlhttp.send(sr);
    // send request
  }

  // teller tonen
  function toonTeller(aantal_tekeningen){
    if (aantal_tekeningen >= counter_min){
      $('.counter').show(0);
      var perc_slider = Math.round(100 *(aantal_tekeningen / counter_max));

      // check of het aantal tekeningen > dan counter_max, toon in dat geval een volle slider ...
      if (Number(aantal_tekeningen) >= Number(counter_max)) {
        perc_slider = 100;
      }

      $('.counter__slider').animate({width: perc_slider+'%', opacity: 1}, 2000, 'easeInOutCubic');
      $('.counter__gettext').html(beautifulThousands(aantal_tekeningen)+' handtekeningen');
      $('.counter__text').fadeIn(2000);
    }
  }

  // makes a number more legible, 1000 becomes 1.000, 12564783 becomes 12.564.783
  function beautifulThousands(mynumber){
    mynumber  += '';
    x = mynumber.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
      x1 = x1.replace(rgx, '$1' + '.' + '$2');
    }
    return x1 + x2;
  }

//  try to get an response from whatsapp, else hide the whatsappbutton
//  ATM not working because ajax doesn't support custom schemes...
  $.ajax({
    type: 'HEAD',
    url: 'whatsapp://send?text=text=Hello%20World!',
    success: function() {
        window.location='whatsapp://send?text=text=Hello%20World!';   
    },
    error: function() {
        $('.gpnl-share-whatsapp').toggle()
    }
  });
});

function getUrlVars(){
  var vars = [], hash;
  var uri = window.location.href.split("#")[0];
  var hashes = uri.slice(window.location.href.indexOf('?') + 1).split('&');
  for(var i = 0; i < hashes.length; i++){
    hash = hashes[i].split('=');
    vars.push(hash[0]);
    vars[hash[0]] = hash[1];
  }
  return vars;
}

function checkKnownEmail(mail) {

}
