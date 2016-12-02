

// url
function cmfRedirect(url) {
	document.location.href = url;
}
function cmfReload() {
	document.location.reload();
}



// Window
function cmfOpenWindow2(url, id) {
	window.open(url, id).focus();
}
function cmfCloseWindow() {
	window.close();
}
function cmfOpenerUrl(url) {
    if(window.opener) {
		window.opener.document.location = url;
		window.close();
	} else {
	    cmfRedirect(url);
	}
}

function pre() {
	if(0) {
	} else {
		if(/*document.console &&*/ console && console.debug) {
			console.debug(arguments);
		} else {
		    var arg2 = new Array();
    		for (i = 0; i < arguments.length; i++) {
    	       arg2[i] = arguments[i];
    		}
		    alert(JSON.stringify(arg2));
		}
	}
}

function ready(func) {
	$(document).ready(func);
}