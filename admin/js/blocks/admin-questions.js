jQuery(document).ready(function() {

  var $ = jQuery;
  var selectId = 'engaging_campaign_ID';
  var containerId = '#' + selectId + '_container';
  var apiUrl = 'http://www.planet4.test/wp-json/planet4-engaging-networks/v1/questions_available';
  var itemId = 417462;

  var questionsContainer = $(containerId);
  var select = $('<select></select>').attr('id', selectId).attr('name', selectId);

  var activeCampaign = parseInt(questionsContainer.attr('data-activecampaign'));
  var activeValid = false;

  $.ajax(apiUrl).then(
    function(res) {

      res = res.filter( (r) => r.id === itemId );
      activeValid = res.find( (r) => r.questionId === activeCampaign );

      if(res.length) {
        select.append($('<option disabled selected value> -- select a campaign -- </option>'));
        $.each(res, function(_, option) {
          select.append($('<option></option>').attr('value', option.questionId).text(option.name));
        });
        questionsContainer.html(select);
      } else {
        questionsContainer.html('No campaigns found');
      }

      if(activeValid) {
        $('#' + selectId).val(activeCampaign);
      }

      if(activeCampaign && !activeValid) {
        questionsContainer.append('<h5>Warning: invalid campaign set</h5>');
      }

    }
  );

});