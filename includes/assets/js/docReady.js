	$('#petitionForm').on('submit', function() {

		// Get the parameter from the petition form and add the action and CSRF protection
		var post_form_value = getFormObj('petitionForm');
		post_form_value["action"] = "petition_form_process";
		post_form_value["nonce"] = petition_form_object.nonce;

		// Disable the form so people don't resubmit
		// $button = $('#petitionForm').find(':button');
		// $button.width( $button.width() ).text('...');
		// $('#petitionForm *').prop("disabled", true);
		$('.petition-form').addClass('flipped');
		$('.petition-thank').removeClass('flipped');

		// Do a ajax call to the wp_admin admin_ajax.php,
		// which triggers our own processing function in the petition block
		$.ajax({
			type:    "POST",
			url:     petition_form_object.ajaxUrl,
			data:    post_form_value,
			success: function(data, response) {
				console.log("^-^");
				console.log(data);
				// placePixel(data);
			},
			error: function(jqXHR, textStatus, errorThrown, data, url){
		//    // Handle errors here
				console.log("o_o");
				// console.log(this.data);
				// console.log(this.url);
			 console.log('ERRORS: ' + textStatus + ': ' + errorThrown);
				console.log(response);
			}
		});
	});

function placePixel (url){
$('#petitionForm').append('<img id="pixel" src="'+url+'" width="1" height="1">');
	// pas als pixel image is geladen de bedanktdiv tonen
}

// Get the key+value from the input fields in the form
function getFormObj(formId) {
	var formObj = {};
	var inputs = $('#'+formId).serializeArray();
	$.each(inputs, function (i, input) {
			formObj[input.name] = input.value;
	});
	return formObj;
}
