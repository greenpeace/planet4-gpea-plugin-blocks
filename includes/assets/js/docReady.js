	$('#gpnl-petitionform').on('submit', function() {
	  // dataLayer.push({'event':'petitiebutton','campaign':'{{campaign_code}}','action':'ga_action','label':'registreer'});


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

function toggleDisable(id) {
	$(id).prop("disabled", !$(id).prop("disabled"));
	
}

function flip(id) {
	$(id).toggleClass('flipped');
}


$( document ).ready(function() {
	$.ajax({
		type: 'HEAD',
		url: 'whatsapp://send?text=text=Hello%20World!',
		success: function() {
			window.location='whatsapp://send?text=text=Hello%20World!';   
		},
		error: function() {
			$('.gpnl-whatsapp-btn').toggle()
		}
	});     
});
