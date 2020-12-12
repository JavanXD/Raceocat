
var data = JSON.parse(decodeURIComponent(window.location.href.split("?")[1]));

document.getElementById("requestId").innerText = data.requestId;
document.getElementById("url").value = data.url;
document.getElementById("method").appendChild(document.createTextNode(data.method));
document.getElementById("type").appendChild(document.createTextNode(data.type));

var tbody = document.getElementById("headersbody");
data.headers.forEach(addHeader);

document.getElementById("ok").onclick = function(){
	browser.runtime.sendMessage({
		headers: getHeaders(),
	});
};

document.getElementById("stop").onclick = function(){
	browser.runtime.sendMessage({
		headers: getHeaders(),
		stop: true
	});
};

document.getElementById("race").onclick = function(){
	do_race(data);
	browser.runtime.sendMessage({
		headers: getHeaders(),
		race: true
	});
};

function do_race(data) {
	console.log("do_race at requestId #" + data.requestId);
	browser.storage.sync.get([data.requestId, "dispatcher", "proxy", "proxyOn", "server"]).then((res) => {
		console.log(res[data.requestId], "requestBody for #" + data.requestId);
		data["body"] = res[data.requestId];
		var dispatcher = res.dispatcher;
		var proxy = res.proxyOn ? '&proxy=' + res.proxy: '';
		var server = res.server ? '&server=' + res.server: '';
		/*
		var xhttp = new XMLHttpRequest();
		xhttp.open("POST", res.dispatcher, true);
		xhttp.setRequestHeader("Content-type", "application/json;charset=UTF-8");
		xhttp.send(JSON.stringify(data));
		*/
		browser.tabs.create({
			url:dispatcher + '?payload=' + encodeURIComponent(JSON.stringify(data)) + proxy + server
		});
	});
}
document.getElementById("addHeader").addEventListener("click", () => addHeader());

function addHeader(header) {
	var row = document.createElement("tr");
	row.classList.add("row");

	var td1 = document.createElement("td");
	var inpt1 = document.createElement("input");
	inpt1.classList.add("headerName");
	if (header)
		inpt1.value = header.name;
	td1.appendChild(inpt1);
	row.appendChild(td1);

	var td2 = document.createElement("td");
	var inpt2 = document.createElement("input");
	inpt2.classList.add("headerValue");
	if (header)
		inpt2.setAttribute("value", header.value);
	td2.appendChild(inpt2);
	row.appendChild(td2);

	var td3 = document.createElement("td");
	var button = document.createElement("button");
	button.textContent = "Remove";
	button.addEventListener("click", () => row.remove());
	td3.appendChild(button);
	row.appendChild(td3);

	tbody.appendChild(row);
}

function getHeaders() {
	var headers = [];
	Array.from(document.querySelectorAll(".row")).forEach(row=>{
		// Headers with empty name or value are ignored by the API.
		headers.push({
			name: row.querySelector(".headerName").value,
			value: row.querySelector(".headerValue").value,
		});
	});

	return headers;
}

function firefox57_workaround_for_blank_panel() {
	browser.windows.getCurrent().then((currentWindow) => {
		browser.windows.update(currentWindow.id, {
			width: currentWindow.width,
			height: currentWindow.height + 1, // 1 pixel more than original size...
		});
	});
}

firefox57_workaround_for_blank_panel();
