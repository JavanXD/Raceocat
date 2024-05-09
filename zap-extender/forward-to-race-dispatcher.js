// Author: Javan Rasokat (javan.rasokat+zap@owasp.org)

// parameters
var dispatcher = "http://localhost/dispatcher.php";
var server = "http://localhost/racer.php"; // add multiple servers with comma
var proxyOn = false;
var proxy = "localhost:8080";

// Script variable to use when unregistering
var popupmenuitemtype = Java.extend(Java.type("org.zaproxy.zap.view.popup.PopupMenuItemHistoryReferenceContainer"));
var curlmenuitem = new popupmenuitemtype("Forward Request to Race Dispatcher") {
	performAction: function(href) {
		invokeWith(href.getHttpMessage());
	}
}

/**
 * This function is called when the script is enabled.
 *
 * @param helper - a helper class which provides 2 methods:
 *		getView() this returns a View object which provides an easy way to add graphical elements.
 *		It will be null is ZAP is running in daemon mode.
 *		getApi() this returns an API object which provides an easy way to add new API calls.
 *	Links to any functionality added should be held in script variables so that they can be removed in uninstall.
 */
function install(helper) {
    if (helper.getView()) {
        helper.getView().getPopupMenu().addMenu(curlmenuitem);
    }
}

function createPayloadFromMessage(msg) {
    var body = msg.getRequestBody().toString();
    body = (body.length() != 0) ? body : '';
	var headers = new Array();
    header = msg.getRequestHeader().getHeadersAsString();
    header = header.split(msg.getRequestHeader().getLineDelimiter());
    for(i=0;i<header.length;i++){
        keyval = header[i].split(": ");
        if(keyval[0] && keyval[1] && keyval[0].trim() != "Host") {
			//blacklisting Host (other blacklisting should also specify here
			headers.push({"name": keyval[0].trim(), "value":keyval[1].trim()});
		}
    }
	var payload = { "method": msg.getRequestHeader().getMethod(),
					"url": msg.getRequestHeader().getURI().toString(),
					"type": "zap",
					"headers": headers,
					"body": body
				  };
    return payload;
}

function invokeWith(msg) {
    payload = createPayloadFromMessage(msg);
    proxy = (proxyOn && proxy) ? '&proxy=' + proxy : '';
    server = (server) ? '&server=' + server : '';
    url = dispatcher + '?payload=' + encodeURIComponent(JSON.stringify(payload)) + proxy + server;
    print(url);

    if (java.awt.Desktop.isDesktopSupported() && java.awt.Desktop.getDesktop().isSupported(java.awt.Desktop.Action.BROWSE)) {
        print("Opened Browser to execute Race script against a selected request.");
        java.awt.Desktop.getDesktop().browse(new java.net.URI(url));
    } else {
        print("Could not find a browser to open a window. OS specific issue.");
    }
}


/**
 * This function is called when the script is disabled.
 *
 * @param helper - a helper class which provides 2 methods:
 *		getView() this returns a View object which provides an easy way to add graphical elements.
 *		It will be null is ZAP is running in daemon mode.
 *		getApi() this returns an API object which provides an easy way to add new API calls.
 */
function uninstall(helper) {
  if (helper.getView()) {
    helper.getView().getPopupMenu().removeMenu(curlmenuitem);
  }
}
