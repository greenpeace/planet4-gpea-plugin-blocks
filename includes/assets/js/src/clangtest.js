/* eslint-disable */
var clang = (function () {
  var exports = {};

  /*
   Base64 library from: http://code.google.com/p/javascriptbase64

   Copyright (c) 2008 Fred Palmer fred.palmer_at_gmail.com

   Permission is hereby granted, free of charge, to any person
   obtaining a copy of this software and associated documentation
   files (the "Software"), to deal in the Software without
   restriction, including without limitation the rights to use,
   copy, modify, merge, publish, distribute, sublicense, and/or sell
   copies of the Software, and to permit persons to whom the
   Software is furnished to do so, subject to the following
   conditions:

   The above copyright notice and this permission notice shall be
   included in all copies or substantial portions of the Software.

   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
   EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
   OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
   NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
   HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
   WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
   FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
   OTHER DEALINGS IN THE SOFTWARE.
   */
  function StringBuffer() {
    this.buffer = []
  }

  function Utf8EncodeEnumerator(a) {
    this._input = a;
    this._index = -1;
    this._buffer = []
  }

  function Base64DecodeEnumerator(a) {
    this._input = a;
    this._index = -1;
    this._buffer = []
  }

  StringBuffer.prototype.append = function (b) {
    this.buffer.push(b);
    return this
  };
  StringBuffer.prototype.toString = function a() {
    return this.buffer.join("")
  };
  var Base64 = {
    codex: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", encode: function (a) {
      var b = new StringBuffer;
      var c = new Utf8EncodeEnumerator(a);
      while (c.moveNext()) {
        var d = c.current;
        c.moveNext();
        var e = c.current;
        c.moveNext();
        var f = c.current;
        var g = d >> 2;
        var h = (d & 3) << 4 | e >> 4;
        var i = (e & 15) << 2 | f >> 6;
        var j = f & 63;
        if (isNaN(e)) {
          i = j = 64
        } else if (isNaN(f)) {
          j = 64
        }
        b.append(this.codex.charAt(g) + this.codex.charAt(h) + this.codex.charAt(i) + this.codex.charAt(j))
      }
      return b.toString()
    }, decode: function (a) {
      var b = new StringBuffer;
      var c = new Base64DecodeEnumerator(a);
      while (c.moveNext()) {
        var d = c.current;
        if (d < 128)b.append(String.fromCharCode(d)); else if (d > 191 && d < 224) {
          c.moveNext();
          var e = c.current;
          b.append(String.fromCharCode((d & 31) << 6 | e & 63))
        } else {
          c.moveNext();
          var e = c.current;
          c.moveNext();
          var f = c.current;
          b.append(String.fromCharCode((d & 15) << 12 | (e & 63) << 6 | f & 63))
        }
      }
      return b.toString()
    }
  };
  Utf8EncodeEnumerator.prototype = {
    current: Number.NaN, moveNext: function () {
      if (this._buffer.length > 0) {
        this.current = this._buffer.shift();
        return true
      } else if (this._index >= this._input.length - 1) {
        this.current = Number.NaN;
        return false
      } else {
        var a = this._input.charCodeAt(++this._index);
        if (a == 13 && this._input.charCodeAt(this._index + 1) == 10) {
          a = 10;
          this._index += 2
        }
        if (a < 128) {
          this.current = a
        } else if (a > 127 && a < 2048) {
          this.current = a >> 6 | 192;
          this._buffer.push(a & 63 | 128)
        } else {
          this.current = a >> 12 | 224;
          this._buffer.push(a >> 6 & 63 | 128);
          this._buffer.push(a & 63 | 128)
        }
        return true
      }
    }
  };
  Base64DecodeEnumerator.prototype = {
    current: 64, moveNext: function () {
      if (this._buffer.length > 0) {
        this.current = this._buffer.shift();
        return true
      } else if (this._index >= this._input.length - 1) {
        this.current = 64;
        return false
      } else {
        var a = Base64.codex.indexOf(this._input.charAt(++this._index));
        var b = Base64.codex.indexOf(this._input.charAt(++this._index));
        var c = Base64.codex.indexOf(this._input.charAt(++this._index));
        var d = Base64.codex.indexOf(this._input.charAt(++this._index));
        var e = a << 2 | b >> 4;
        var f = (b & 15) << 4 | c >> 2;
        var g = (c & 3) << 6 | d;
        this.current = e;
        if (c != 64)this._buffer.push(f);
        if (d != 64)this._buffer.push(g);
        return true
      }
    }
  }
  /* end base64 library */

  exports.conversion = (function () {
    var uid = '';

    var findClangCtParam = function () {
      var qs = window.location.search;
      if (window.location.search[0] == '?') {
        qs = qs.substr(1);
      }
      var params = {};
      var parts = qs.split('&');
      for (var i = 0; i < parts.length; i++) {
        var kv = parts[i].split('=');
        if (kv.length == 2) {
          params[kv[0]] = decodeURIComponent(kv[1]);
        }
        else {
          params[kv[0]] = true;
        }
      }
      if (params.clangct) {
        console.log(params.clangct);
        var z = arguments.callee.name;

      }
      return;
    };
    var findTrackingKeywords = function () {
      var clangct = findClangCtParam();
      if (clangct) {
        var ctParam = clangct.split('.');
        while (ctParam[1].length % 4) {
          ctParam[1] += '=';
        }
        var keywords = Base64.decode(ctParam[1].replace(/-/g, '+').replace(/_/g, '/')).split(',');
        var result = {};
        for (var i in keywords) {
          result[keywords[i]] = 1;
        }
        return result;
      }
      return;
    };
    var findTrackingId = function () {
      var clangct = findClangCtParam();
      if (clangct) {
        var ctParam = clangct.split('.');
        return ctParam[0];
      }
      return;
    };
    var encodeKeywords = function (keywords) {
      var result = [];
      for (var i in keywords) {
        result.push(i + '=' + keywords[i]);
      }
      return Base64.encode(result.join('&')).replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '');
    };

    return {
      track: function (track, id) {
        if (typeof track === 'undefined') {
          var track = findTrackingKeywords();
        }
        if (typeof id === 'undefined') {
          var id = findTrackingId();
        }
        else {
          var id = id.split('.')[0];
        }

        var imageUrl = '//secure.myclang.com/14/' + uid + '.' + id + '.' + encodeKeywords(track);

        try {
          if (document.getElementsByTagName('body')[0]) {
            var img = document.createElement('img');
            img.src = imageUrl;
            document.getElementsByTagName('body')[0].appendChild(img);
            return;
          }
        }
        catch (e) {
        }

        document.write('<img src="' + imageUrl + '" />');
      },
      init: function (_uid) {
        uid = _uid;
      },
      initLinks: function () {
        var addArgumentToLinks = function () {
          var elements = document.getElementsByTagName('a');
          for (var i = 0; i < elements.length; i++) {
            var element = elements[i];
            var href = element.getAttribute('href');
            if (href) {
              if (!href.match(/(&|\?)clangct=/) && !href.match(/^#/) && !href.match(/^mailto:/i)) {
                var clangct = findClangCtParam();
                if (clangct) {
                  var anchor = '';
                  if (href.indexOf('#') != -1) {
                    anchor = href.substr(href.indexOf('#'));
                    href = href.substring(0, href.indexOf('#'));
                  }

                  if (href.match(/\?/)) {
                    href = href + '&clangct=' + encodeURIComponent(clangct);
                  }
                  else {
                    href = href + '?clangct=' + encodeURIComponent(clangct);
                  }
                  if (anchor != '') {
                    href = href + anchor;
                  }
                  element.setAttribute('href', href);
                }
              }
            }
          }
        }
        if (document.getElementsByTagName('body')[0]) {
          addArgumentToLinks();
        }
        else {

          window.onload = (function (oldOnload, addArgumentToLinks) {
            return function () {
              if (oldOnload)oldOnload();
              addArgumentToLinks();
            }
          })(window.onload, addArgumentToLinks);
        }
      },
      formatNumber: function (value, thousandSep, decimalSep) {
        if (typeof value == 'undefined')return '';
        if (typeof thousandSep == 'undefined')thousandSep = ',';
        if (typeof decimalSep == 'undefined')decimalSep = '.';

        var escape = function (text) {
          if (!arguments.callee.sRE) {
            var specials = [
              '/', '.', '*', '+', '?', '|',
              '(', ')', '[', ']', '{', '}', '\\'
            ];
            arguments.callee.sRE = new RegExp(
              '(\\' + specials.join('|\\') + ')', 'g'
            );
          }
          return text.replace(arguments.callee.sRE, '\\$1');
        }
        var regExp = new RegExp('(' + escape(thousandSep) + '|' + escape(decimalSep) + ')', 'g');
        return parseFloat(value.replace(regExp, function (m) {
          if (m == thousandSep) return '';
          if (m == decimalSep) return '.';
        }));
      }
    };
  })();

  return exports;
})();
