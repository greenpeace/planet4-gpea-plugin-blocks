	$('#gpnl-petitionform').on('submit', function() {

		// Get the parameter from the petition form and add the action and CSRF protection
		var post_form_value = getFormObj('gpnl-petitionform');
		post_form_value["action"] = "petition_form_process";
		post_form_value["nonce"] = petition_form_object.nonce;

		// Disable the form so people can't resubmit
		toggle('#gpnl-petitionform *');

		// Do a ajax call to the wp_admin admin_ajax.php,
		// which triggers processing function in the petition block
		$.ajax({
			type:    "POST",
			url:     petition_form_object.ajaxUrl,
			data:    post_form_value,
			success: function(data, response) {
				console.log("^-^");
				console.log(data);
				flip('.gpnl-petition');
			},
			error: function(jqXHR, textStatus, errorThrown, data, url){
				console.log("o_o");
				console.log('ERRORS: ' + textStatus + ': ' + errorThrown);
				$('.gpnl-petition-thank').empty()
				$('.gpnl-petition-thank').append("<p>Sorry, er gaat momenteel iets fout, probeer het nu of later opnieuw.</p>")
				$('.gpnl-petition-thank').append("<button type=\"button\" class=\"btn btn-primary\" onclick=\"flip('.gpnl-petition');toggle('#gpnl-petitionform *');\">Probeer opnieuw</button>")
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

function toggle(id) {
	$(id).prop("disabled", !$(id).prop("disabled"));
}

function flip(id) {
	$(id).toggleClass('flipped');
}