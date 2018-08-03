(function($){
	
	$('#petitionForm').on('submit', function() {
    var post_form_value = jQuery(this).serialize();
    jQuery.ajax({
      action:  'petition_form',
      type:    "POST",
      url:     theUniqueNameForOurJSObjectPetitionForm.admin_url,
      data:    post_form_value,
      success: function(data) {
         jQuery("#petitionForm").html(data);
      }
    });
		
		
		/*
		var options = { 
			url: theUniqueNameForOurJSObjectPetitionForm.ajaxUrl, // this is part of the JS object you pass in from wp_localize_scripts.
			type: 'post', // 'get' or 'post', override for form's 'method' attribute 
			dataType:  'json' , // 'xml', 'script', or 'json' (expected server response type) 
			success : function(responseText, statusText, xhr, $form) {
				$('#petitionForm').html('Jaaaaaaaaaa, gelukt!');
			},
			// use beforeSubmit to add your nonce to the form data before submitting.
			beforeSubmit : function(arr, $form, options){
				arr.push( { "name" : "nonce", "value" : theUniqueNameForOurJSObjectPetitionForm.nonce });
			},
    }; 
    // you should probably use an id more unique than "form"
    $('#petitionForm').ajaxForm(options);
		*/
		
		
		
		/*
		var post_form_value = getFormObj('petitionForm');
		$.ajax({
			url: 'iets/naar_iets.php',
			type: 'POST',
			data: post_form_value,
			success: function(msg){
				console.log(msg);
			},
			error: function(jqXHR, textStatus, errorThrown){
				// Handle errors here
				console.log('ERRORS: ' + textStatus + ': ' + errorThrown);
			}
		});
		*/
	});

})(jQuery);

/*
// get form object
function getFormObj(formId) {
	var formObj = {};
	var inputs = $('#'+formId).serializeArray();
	$.each(inputs, function (i, input) {
			formObj[input.name] = input.value;
	});
	return formObj;
}
*/