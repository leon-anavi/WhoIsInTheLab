(function() {

var BASE_URL = "http://87.97.198.36/who/";

function checkMinVersion(minVersion, version) {
  var $vrs = version.split('.'),
      min  = minVersion.split('.');
  for (var i=0, len=$vrs.length; i<len; i++) {
    if (min[i] && (+$vrs[i]) < (+min[i])) {
      return false;
    }
  }
  return true;
}

// Localize jQuery variable
var jQuery;

/******** Load jQuery if not present *********/
if (window.jQuery === undefined || checkMinVersion('1.9.0', window.jQuery.fn.jquery)) {
    var script_tag = document.createElement('script');
    script_tag.setAttribute("type","text/javascript");
    script_tag.setAttribute("src","http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js");
    if (script_tag.readyState) {
      script_tag.onreadystatechange = function () { // For old versions of IE
          if (this.readyState == 'complete' || this.readyState == 'loaded') {
              scriptLoadHandler();
          }
      };
    } else { // Other browsers
      script_tag.onload = scriptLoadHandler;
    }
    // Try to find the head, otherwise default to the documentElement
    (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag);
} else {
    // The jQuery version on the window is the one we want to use
    jQuery = window.jQuery;
    main();
}

/******** Called once jQuery has loaded ******/
function scriptLoadHandler() {
    // Restore $ and window.jQuery to their previous values and store the
    // new jQuery in our local jQuery variable
    jQuery = window.jQuery.noConflict(true);
    // Call our main function
    main(); 
}

/******** Our main function ********/
function main() { 
    jQuery(document).ready(function($) { 
        /******* Load CSS *******/
        var css_link = $("<link>", { 
            rel: "stylesheet", 
            type: "text/css", 
            href: BASE_URL+"widget.css" 
        });
        css_link.appendTo('head');          

        /******* Load HTML *******/
        var widget_url = BASE_URL+"api.php?callback=?"
        $.getJSON(widget_url, function(data) {
          $('#hackafe-widget').html(data.html);
        });
    });
}

})(); // We call our anonymous function immediately
