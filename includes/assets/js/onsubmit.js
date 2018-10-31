$('#gpnl-petitionform').on('submit', function () {
    // Get the parameter from the petition form and add the action and CSRF protection
    var post_form_value = getFormObj('gpnl-petitionform');
    post_form_value.action = "petition_form_process";
    post_form_value.nonce  = petition_form_object.nonce;
    post_form_value.ad_campaign = petition_form_object.ad_campaign;

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
                dataLayer.push({
                    'event'         :'petitiebutton',
                    'conv_campaign' :petition_form_object.analytics_campaign,
                    'conv_action'   :petition_form_object.ga_action,
                    'conv_label'    :'registreer'
                });
            }
            else{
                console.log("GTM not defined?")
            }

            // if the consent was ticked or consent was given by entering phonenumber
            if (post_form_value.consent === "on" || post_form_value.phone !== "") {
                // If an ad campaign is run by an external company fire the conversiontracking
                if (petition_form_object.ad_campaign === 'SB') {
                    fbq('track', 'Lead');
                    // if it is run by social blue, also deduplicate
                    socialBlueDeDuplicate(post_form_value['mail'], data['data']['phone'], petition_form_object.apref)
                } else if (petition_form_object.ad_campaign === 'JA') {
                    fbq('track', 'Lead');
                }
            }

            // flip the card, positionattribute flips to make sure no problems arises with different lengths of the front and back of the card, finally hide the front
            flip();
            openMailTarget(petition_form_object.mailto);
        },
        error: function(jqXHR, textStatus, errorThrown, data, url){
            console.log("o_o");
            console.log('ERRORS: ' + textStatus + ': ' + errorThrown);
            // If the backend send an error, hide the thank element and show an error urging to try again
            flip();
            $('.gpnl-petition-thank *').hide('');
            $('.gpnl-petition-thank').append("<p>Sorry, er gaat momenteel iets fout, probeer het nu of later opnieuw.</p>")
            $('.gpnl-petition-thank').append("<button type=\"button\" class=\"btn btn-primary btn-block\" onclick=\"flip('.gpnl-petition');toggleDisable('#gpnl-petitionform *');$('.gpnl-petition-form').show();$('#signBtn').toggle();$('.gpnl-petition-thank').css( 'position', 'absolute');$('.gpnl-petition-form').css( 'position', 'relative');\">Probeer opnieuw</button>")
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
function flip() {
    // first hide the signing button
    $('#signBtn').toggle();
    // then flip the position attribute on the front and back of the card to support different lengths front and back
    $(".gpnl-petition-thank").css( "position", "relative");
    $(".gpnl-petition-form").css( "position", "absolute");
    // then flip the card
    $('.gpnl-petition').toggleClass('flipped');
    // after animation hide the front
    cardfront.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend',
    function(e) {
        $('.gpnl-petition-form').hide();
    });
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

function openMailTarget(link) {
	window.open(link);
}
