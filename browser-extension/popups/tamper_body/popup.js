
var data = JSON.parse(decodeURIComponent(window.location.href.split("?")[1]));

document.getElementById("requestId").innerText = data.requestId;
document.getElementById("url").value = data.url;
document.getElementById("method").appendChild(document.createTextNode(data.method));
document.getElementById("type").appendChild(document.createTextNode(data.type));

var norq = document.getElementById("norq");
var bodytbl = document.getElementById("body");
if(data.body.length) {
	norq.parentNode.removeChild(norq);
	bodytbl.value = data.body;
} else {
	bodytbl.parentNode.removeChild(bodytbl);
}

document.getElementById('ok').onclick = function(){
	let jsonObj = {};
	jsonObj[data.requestId] = data.body;
	browser.storage.sync.set(jsonObj, function() {
		console.log("Saved a new request with it's requestBody to storage " + data.requestId);
		var url = document.getElementById("url").value;
		browser.runtime.sendMessage({
			cancel: false,
			redirect: data.url != url ? url : false
		});
	});
};

document.getElementById('stop').onclick = function(){
	var url = document.getElementById("url").value;
	browser.runtime.sendMessage({
		cancel: false,
		stop: true,
		redirect: data.url != url ? url : false
	});
};

document.getElementById('cancel').onclick = function(){
	browser.runtime.sendMessage({
		cancel: true
	});
};

function firefox57_workaround_for_blank_panel() {
	browser.windows.getCurrent().then((currentWindow) => {
		browser.windows.update(currentWindow.id, {
			width: currentWindow.width,
			height: currentWindow.height + 1, // 1 pixel more than original size...
		});
	});
}

firefox57_workaround_for_blank_panel();
