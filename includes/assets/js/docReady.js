	$('#gpnl-petitionform').on('submit', function() {

		// Get the parameter from the petition form and add the action and CSRF protection
		var post_form_value = getFormObj('gpnl-petitionform');
		// post_form_value["action"] = "petition_form_process";
		// post_form_value["nonce"] = petition_form_object.nonce;

		// Disable the form so people don't resubmit
		$button = $('#petitionForm').find(':button');
		$button.width( $button.width() ).text('...');
		// $('#petitionForm *').prop("disabled", true);
		placePixel(post_form_value);


		// Do a ajax call to the wp_admin admin_ajax.php,
		// which triggers our own processing function in the petition block
		// $.ajax({
		// 	type:    "POST",
		// 	url:     petition_form_object.ajaxUrl,
		// 	data:    post_form_value,
		// 	success: function(data, response) {
		// 		console.log("^-^");
		// 		console.log(data);
		// 	},
		// 	error: function(jqXHR, textStatus, errorThrown, data, url){
		// //    // Handle errors here
		// 		console.log("o_o");
		// 		// console.log(this.data);
		// 		// console.log(this.url);
		// 	 console.log('ERRORS: ' + textStatus + ': ' + errorThrown);
		// 		console.log(response);
		// 	}
		// });
	});

function placePixel (url){
// $('#petitionForm').append('<img id="pixel" src="'+url+'" width="1" height="1">');
var pixel_url = 'https://www.mygreenpeace.nl/registreren/pixel.aspx';
var marketingcode = getFormObj('gpnl-petitionform')['marketingcode'];
var literatuurcode = getFormObj('gpnl-petitionform')['literatuurcode'];
var fn = getFormObj('gpnl-petitionform')['name'];
var email = getFormObj('gpnl-petitionform')['mail'];
var tel = getFormObj('gpnl-petitionform')['phone'];
var akkoord = getFormObj('gpnl-petitionform')['consent'];
$('#gpnl-petitionform').append('<p id="pixel">'+pixel_url+'?source='+marketingcode+'&per='+literatuurcode+'&fn='+fn+'&email='+email+'&tel='+tel+'&stop='+akkoord+'</p>');
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
