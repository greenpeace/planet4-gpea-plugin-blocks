var treshold = 3;
var liveblog = $('.gpnl--liveblog');
var elements = liveblog.find('article');
var numelements = elements.length;
var hiddenelements = {};
var showmorebutton = '<button type="button" name="showmore" id="showmore" class="btn btn-primary btn-lg btn-block" onclick="showMore()">Lees meer</button>';

if (numelements > treshold) {
  hiddenelements = elements.slice(treshold, numelements);
  hiddenelements.hide();
  liveblog.append(showmorebutton);

function showMore() {
  var newelements = hiddenelements.slice(0, treshold)
  hiddenelements = hiddenelements.slice(treshold, hiddenelements.length);
  newelements.show();
  if (hiddenelements.length === 0) {
    $('#showmore').hide();
  }
}

(function readabledates() {
  $.each($('.gpnl--liveblog article small'), function (key, value) {
    var displaydate = moment(value.innerText).fromNow();
    $(this).html(displaydate);
  });
}());
