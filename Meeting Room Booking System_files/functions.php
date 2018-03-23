
$.fn.reverse = [].reverse;

function getErrorList(errors)
{
  var result = {html: '', text: ''};
  var patternSpan = /<span[\s\S]*span>/gi;
  var patternTags = /<\S[^><]*>/g;
  result.html += "<ul>";
  for (var i=0; i<errors.length; i++)
  {
    result.html += "<li>" + errors[i] + "<\/li>";
    result.text += '(' + (i+1).toString() + ') ';
        result.text += errors[i].replace(patternSpan, '').replace(patternTags, '') + "  \n";
  }
  result.html += "<\/ul>";
  return result;
}


var visibilityPrefix = function visibilityPrefix() {
    var prefixes = ['', 'webkit', 'moz', 'ms', 'o'];
    var testProperty;
    
    if (typeof visibilityPrefix.prefix === 'undefined')
    {
      visibilityPrefix.prefix = null;
      for (var i=0; i<prefixes.length; i++)
      {
        testProperty = prefixes[i];
        testProperty += (prefixes[i] === '') ? 'hidden' : 'Hidden';
        if (testProperty in document)
        {
          visibilityPrefix.prefix = prefixes[i];
          break;
        }
      }
    }

    return visibilityPrefix.prefix;
  };

var isHidden = function isHidden() {
    var prefix;
    prefix = visibilityPrefix();
    switch (prefix)
    {
      case null:
        return null;
        break;
      case '':
        return document.hidden;
        break;
      default:
        return document[prefix + 'Hidden'];
        break;
    }
  };


function throttle(fn, threshold, scope) {

  var last,
      deferTimer;
      
  threshold || (threshold = 250);
  
  return function () {
    var context = scope || this,
        now = +new Date,
        args = arguments;
        
    if (last && now < last + threshold)
    {
      // hold on to it
      clearTimeout(deferTimer);
      deferTimer = setTimeout(function () {
          last = now;
          fn.apply(context, args);
        }, threshold);
    }
    else 
    {
      last = now;
      fn.apply(context, args);
    }
  };
}

function isMeteredConnection()
{
  var connection = navigator.connection || 
                   navigator.mozConnection || 
                   navigator.webkitConnection ||
                   navigator.msConnection ||
                   null;
  
  if (connection === null)
  {
    return false;
  }
  
  if ('type' in connection)
  {
        return (connection.type === 'cellular');
  }
  
    if ('metered' in connection)
  {
    return connection.metered;
  }
  
  return false;
}