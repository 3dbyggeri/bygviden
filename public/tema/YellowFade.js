// This technique is a combination of a technique I used for highlighting FAQ's using anchors 
// and the ever popular yellow-fade technique used by 37 Signals in Basecamp.

// Including this script in a page will automatically do two things when the page loads...
// 1. Highlight a target item from the URL (browser address bar) if one is present.
// 2. Setup all anchor tags with targets pointing to the current page to cause a fade on the target element when clicked.

// This is the amount of time (in milliseconds) that will lapse between each step in the fade
var FadeInterval = 600;

// This is where the fade will start, if you want it to be faster and start with a lighter color, make this number smaller
// It corresponds directly to the FadeSteps below
var StartFadeAt = 7;

// This is list of steps that will be used for the color to fade out
var FadeSteps = new Array();
	FadeSteps[1] = "f6f7e7";
	FadeSteps[2] = "F4F4F4";
	FadeSteps[3] = "EEEEEE";
	FadeSteps[4] = "EAEAEA";
	FadeSteps[5] = "DEDEDE";
	FadeSteps[6] = "D8D8D8";
	FadeSteps[7] = "C6C6C6";

// These are the lines that "connect" the script to the page.
var W3CDOM = (document.createElement && document.getElementsByTagName);
addEvent(window, 'load', initFades);

// This function automatically connects the script to the page so that you do not need any inline script
// See http://www.scottandrew.com/weblog/articles/cbs-events for more information
function addEvent(obj, eventType,fn, useCapture)
{
	if (obj.addEventListener) {
		obj.addEventListener(eventType, fn, useCapture);
		return true;
	} else {
		if (obj.attachEvent) {
			var r = obj.attachEvent("on"+eventType, fn);
			return r;
		}
	}
}

// The function that initializes the fade and hooks the script into the page
function initFades()
{
	if (!W3CDOM) return;
    
// This section highlights targets from the URL (browser address bar)
    
    // Get the URL
    var currentURL = unescape(window.location);
    // If there is a '#' in the URL
    if (currentURL.indexOf('#')>-1) 
        // Highlight the target
        DoFade(StartFadeAt, currentURL.substring(currentURL.indexOf('#')+1,currentURL.length));
    

// This section searches the page body for anchors and adds onclick events so that their targets get highlighted
    
    // Get the list of all anchors in the body
    var anchors = document.body.getElementsByTagName('a');

    // For each of those anchors
    for (var i=0;i<anchors.length;i++)

        // If there is a '#' in the anchors href
        if (anchors[i].href.indexOf('#')>-1)

            // Add an onclick event that calls the highlight function for the target
            anchors[i].onclick = function(){Highlight(this.href);return true};
}

// This function is just a small wrapper to use for the oncick events of the anchors
function Highlight(target) {

	// Get the target ID from the string that was passed to the function
    var targetId = target.substring(target.indexOf('#')+1,target.length);
    DoFade(StartFadeAt, targetId);
}

// This is the recursive function call that actually performs the fade
function DoFade(colorId, targetId) {
    if (colorId >= 1) {
		document.getElementById(targetId).style.backgroundColor = "#" + FadeSteps[colorId];
		
        // If it's the last color, set it to transparent
        if (colorId==1) {
            //document.getElementById(targetId).style.backgroundColor = "transparent";
		}
        colorId--;
		
        // Wait a little bit and fade another shade
        setTimeout("DoFade("+colorId+",'"+targetId+"')", FadeInterval);
	}
}

