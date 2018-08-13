  $('#petitionForm').on('submit', function() {
    var post_form_value = getFormObj('petitionForm');
    post_form_value["action"] = "petition_form_process";
    post_form_value["nonce"] = petition_form_object.nonce;

    $button = $('#petitionForm').find(':button');
    $button.width( $button.width() ).text('...');
    $('#petitionForm *').prop("disabled", true);


    console.log(post_form_value);

    $.ajax({
      type:    "POST",
      url:     petition_form_object.ajaxUrl,
      data:    post_form_value,
      success: function(data, response) {
        console.log("^-^");
        console.log(data);
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


// get form object
function getFormObj(formId) {
	var formObj = {};
	var inputs = $('#'+formId).serializeArray();
	$.each(inputs, function (i, input) {
			formObj[input.name] = input.value;
	});
	return formObj;
}
