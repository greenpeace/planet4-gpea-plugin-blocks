let clangct=getUrlVars()['clangct'];

if(clangct != undefined){
  $.ajax({
    url: 'https://secure.myclang.com/pub/resources/extern/js/clang.js',
    dataType: 'script' })
    .success(function(){ clang.conversion.init(clangct);});
}

// REFACTOR IE11 doesn't support UrlSearchParams, so custom UrlParam function.
// 	Consider polyfilling it now? or wait until we drop IE11 support and switch then?
function getUrlVars(){
  var vars = [],
    hash;
  var uri = window.location.href.split('#')[0];
  var hashes = uri.slice(window.location.href.indexOf('?') + 1).split('&');
  for(var i = 0; i < hashes.length; i++){
    hash = hashes[i].split('=');
    vars.push(hash[0]);
    vars[hash[0]] = hash[1];
  }
  return vars;
}
