	$('#gpnl-petitionform').on('submit', function() {
		// Get the parameter from the petition form and add the action and CSRF protection
		var post_form_value = getFormObj('gpnl-petitionform');
		post_form_value["action"] = "petition_form_process";
		post_form_value["nonce"] = petition_form_object.nonce;

		cardfront = $('.gpnl-petition');

		// Disable the form so people can't resubmit
		toggleDisable('#gpnl-petitionform *');

		// Do a ajax call to the wp_admin admin_ajax.php,
		// which triggers processing function in the petition block
		$.ajax({
			type:    "POST",
			url:     petition_form_object.ajaxUrl,
			data:    post_form_value,
			success: function(data, response) {
				console.log("^-^");
				console.log(data);

				// Send conversion event to the GTM
				if (typeof dataLayer !== 'undefined') {
					dataLayer.push({'event':'petitiebutton','campaign':petition_form_object.analytics_campaign,'action':petition_form_object.ga_action,'label':'registreer'});
				}
				else{
					console.log("GTM not defined?")
				}

				// flip the card, with some positionattribute flips to make sure no problems arises with different lengths of the front and back of the card
				$('#signBtn').toggle();
				$(".gpnl-petition-thank").css( "position", "relative");				
				$(".gpnl-petition-form").css( "position", "absolute");				
				flip('.gpnl-petition');
				cardfront.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend',   
				function(e) {
					$('.gpnl-petition-form').hide();
				});
			},
			error: function(jqXHR, textStatus, errorThrown, data, url){
				console.log("o_o");
				console.log('ERRORS: ' + textStatus + ': ' + errorThrown);
				// If the backend send an error, hide the thank element and show an error urging to try again
				$('.gpnl-petition-thank').empty()
				$('.gpnl-petition-thank').append("<p>Sorry, er gaat momenteel iets fout, probeer het nu of later opnieuw.</p>")
				$('.gpnl-petition-thank').append("<button type=\"button\" class=\"btn btn-primary btn-block\" onclick=\"flip('.gpnl-petition');toggleDisable('#gpnl-petitionform *');$('#gpnl-petition-form').toggle();$('#signBtn').toggle();$('.gpnl-petition-thank').css( 'position', 'absolute');$('.gpnl-petition-form').css( 'position', 'relative');\">Probeer opnieuw</button>")
			}
		});
	});

// Get the key+value from the input fields in the form
function getFormObj(formId) {
	var formObj = {};
	var inputs = $('#'+formId).serializeArray();
	$.each(inputs, function (i, input) {
			formObj[input.name] = input.value;
	});
	return formObj;
}

// Toggle the disabled state on form elements
function toggleDisable(id) {
	$(id).prop("disabled", !$(id).prop("disabled"));
	
}

// toggle the flipped class for the card parent
function flip(id) {
	$(id).toggleClass('flipped');
}

//  try to get an response from whatsapp, else hide the whatsappbutton
//  ATM not working because ajax doesn't support custom schemes...
$( document ).ready(function() {
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

function fireShareEvent (platform){
	if (typeof dataLayer !== 'undefined') {
		dataLayer.push({'event':'petitiebutton','campaign':petition_form_object.analytics_campaign,'action':petition_form_object.ga_action,'label':'share_'+platform});
	}
	else{
		console.log("GTM not defined?")
	}
}