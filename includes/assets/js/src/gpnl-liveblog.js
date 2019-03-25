var treshold = 3;
var liveblog = $('.gpnl--liveblog');
var elements = liveblog.find('article');
var numelements = elements.length;
var hiddenelements = {};
var showmorebutton = '<button type="button" name="showmore" id="showmore" class="btn btn-primary btn-lg btn-block" onclick="showMore()">Lees meer</button>';

if (numelements > treshold) {
  hiddenelements = elements.slice(treshold, numelements);
  hiddenelements.hide();
  liveblog.after(showmorebutton);
}
// eslint-disable-next-line no-unused-vars
function showMore() {
  var newelements = hiddenelements.slice(0, treshold);
  hiddenelements = hiddenelements.slice(treshold, hiddenelements.length);
  newelements.show();
  if (hiddenelements.length === 0) {
    $('#showmore').hide();
  }
}

/**
 * Converts nested articles in the liveblog to siblings.
 *
 * To ease the work of the editor it is not necessary to add closing tags to a liveblog item
 * This results in a liveblog with nested articles. For accessibility these articles are converted to siblings
 */
(function flattenBlog() {
  $.each($('.gpnl--liveblog').find('article'),function (key, element) {
    if (key === 0){	return; }
    $(element).appendTo(liveblog);
  });
}());

/**
 *  Converts all dates with the specified moment--date class to a human comprehensible format.
 *  IE 2018-03-11 15:00 to a string like "5 hours ago" compared to current time
 */
(function readabledates() {
  $.each($('.gpnl--liveblog .moment--date'), function (key, value) {
    var displaydate = moment(value.innerText).fromNow();
    $(this).html(displaydate);
  });
}());


