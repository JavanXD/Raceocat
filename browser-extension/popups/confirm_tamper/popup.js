
document.getElementById("n").onclick = ()=>respond(false);
document.getElementById("y").onclick = ()=>respond(true);

function respond(r) {
	var types = [];
	Array.from(document.querySelectorAll(".types")).forEach(inp=>{
		if(inp.checked) types.push(inp.value);
	});
	browser.runtime.sendMessage({
		tamper: r,
		types: types,
		tab: document.getElementById("tab").checked,
		pattern: document.getElementById("matchregex").value
	});
}

function load_last_options() {
	const last_options = JSON.parse(decodeURIComponent(window.location.href.split("?")[1]));

	last_options.types = last_options.types.reduce((acc, value) => {
		acc[value] = true;
		return acc;
	}, {});
	if (Object.keys(last_options.types).length === 0)
		last_options.types = { main_frame: true, xmlhttprequest: true };
	document.querySelectorAll(".types").forEach((elem) => {
		elem.checked = last_options.types[elem.value];
	});

	document.querySelector("#matchregex").value = last_options.pattern || "(.*?)";
	document.querySelector("#tab").checked = last_options.tab || true;
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
load_last_options();
