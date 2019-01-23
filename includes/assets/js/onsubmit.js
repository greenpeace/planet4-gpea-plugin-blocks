$('.gpnl-petitionform').on('submit', function () {
	var petition_form_element = this;
    // Get the  parameter from the petition form and add the action and CSRF protection
    var post_form_value = getFormObj(petition_form_element);
    post_form_value.action = "petition_form_process";
    post_form_value.nonce  = petition_form_object.nonce;
    post_form_value.ad_campaign = petition_form_object.ad_campaign;

    // Disable the form so people can't resubmit
    toggleDisable($(petition_form_element).find('*'));

    // Do a ajax call to the wp_admin admin_ajax.php,
    // which triggers processing function in the petition block
    $.ajax({
        type:    "POST",
        url:     petition_form_object.ajaxUrl,
        data:    post_form_value,
        success: function(data, response) {
            console.log("^-^");

            // Send conversion event to the GTM
            if (typeof dataLayer !== 'undefined') {
                dataLayer.push({
                    'event'         :'petitiebutton',
                    'conv_campaign' :petition_form_object.analytics_campaign,
                    'conv_action'   :petition_form_object.ga_action,
                    'conv_label'    :'registreer'
                });
            }

            // if the consent was ticked or consent was given by entering phonenumber
            if (post_form_value.consent === "on" || post_form_value.phone !== "") {
                // If an ad campaign is run by an external company fire the conversiontracking
                if (petition_form_object.ad_campaign === 'SB') {
                    fbq('track', 'Lead');
                    // if it is run by social blue, also deduplicate
                    socialBlueDeDuplicate(post_form_value['mail'], data['data']['phone'], petition_form_object.apref)
                } else if (petition_form_object.ad_campaign === 'JA') {
                    fbq('track', petition_form_object.jalt_track);
                }
            }

            // cardflip the card, positionattribute flips to make sure no problems arises with different lengths of the front and back of the card, finally hide the front
            cardflip(petition_form_element);
		},
		error: function(jqXHR, textStatus, errorThrown, data, url){
			// If the backend sends an error, hide the thank element and show an error urging to try again
			console.log("o_o");
			console.log('ERRORS: ' + textStatus + ': ' + errorThrown);
			cardback = $(petition_form_element.parentNode.nextElementSibling);
			cardback.find('*').hide('');
			cardback.append("<p>Sorry, er gaat momenteel iets fout, probeer het nu of later opnieuw.</p>")
			cardback.append(
			"<a href='"+window.location.href +"' class=\"btn btn-primary btn-block\"" +
				"\">Probeer opnieuw</a>");
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
	el.prop("disabled", !el.prop("disabled"));

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
	$(parent.nextElementSibling).css( "position", flip_positionattribute(parent.nextElementSibling));
    $(parent).css( "position", flip_positionattribute(parent));
	// then cardflip the card
	card.toggleClass('flipped');

	// after animation hide the front
    card.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend',
		function(e) {
			$(parent).toggle();
			card.off('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend');
		});

}

function flip_positionattribute (el){
	return $(el).css('position') === "absolute" ? 'relative' : 'absolute'
}

function fireShareEvent (platform){
    if (typeof dataLayer !== 'undefined') {
        dataLayer.push({
            'event'         :'petitiebutton',
            'conv_campaign' :petition_form_object.analytics_campaign,
            'conv_action'   :petition_form_object.ga_action,
            'conv_label'    :'share_' + platform});
    }
    else{
        console.log("GTM not defined?")
    }
}

// Send the supporter data to for deduplication
function socialBlueDeDuplicate(email, phone, apref) {
    var apHost = ("https:" == document.location.protocol ? "https://secure.event." : "http://www.") + "affiliatepartners.com";
    var apSrc = "/js/ApConversionPixelV1.1.js";
    _apOrderValue = 0;
    _apOrderNumber = 'email=' + email + '-telefoonnumer=' + phone;
    _apRef = apref;

    try {
        $('body').append(unescape("%3Cscript src='" + apHost + apSrc + "' type='text/javascript'%3E%3C/script%3E"))
    } catch (err) {
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = apHost + apSrc;
        document.getElementsByTagName('head')[0].appendChild(script);
    }
}
