function saveOptions(e) {
    var server = "";
    var els = document.querySelectorAll('input');
    for (var i = 0; i < els.length; i++)
    {
        if (els[i].name.indexOf("server") > -1)
        {
            server = server + els[i].value + ",";
        }
    }
    server = server.replace(/,\s*$/, ""); // remove last comma

    // prefs object
    let prefs = {
        dispatcher : document.querySelector("#dispatcher").value,
        proxy      : document.querySelector("#proxy").value,
        proxyOn    : document.querySelector("#proxyOn").checked,
        server    : server
    }

    browser.storage.sync.set(prefs);

    // refresh options
    restoreOptions();

    e.preventDefault();
}

function restoreOptions() {
    browser.storage.sync.get("proxyOn").then((res) => {
        document.querySelector("#proxyOn").checked = res.proxyOn || false;
    });
    browser.storage.sync.get("dispatcher").then((res) => {
        document.querySelector("#dispatcher").value = res.dispatcher || "http://localhost:80/dispatcher.php";
    });
    browser.storage.sync.get("proxy").then((res) => {
        document.querySelector("#proxy").value = res.proxy || "localhost:8080";
    });
    browser.storage.sync.get("server").then((res) => {
        var serverBody = document.getElementById('serverBody');
        serverBody.innerHTML = '';
        if(res.server) {
            var serverArray = res.server.split(",").filter(item => item);
            serverArray.forEach(function(server) {
                addServer(undefined, server);
            });
            if(serverArray.length < 1) {
                addServer(undefined, "http://localhost:80/racer.php");
            }
        }else {
            addServer(undefined, "http://localhost:80/racer.php");
        }
    });
}

function addServer(server, value="") {
    var div = document.createElement("div");
    var input = document.createElement("input");

    input.setAttribute("type", "text");
    input.setAttribute("name", "server[]");
    input.setAttribute("placeholder", "http://localhost:80/racer.php");
    input.setAttribute("value", value);
    div.appendChild(input);

    var serverBody = document.getElementById('serverBody');
    serverBody.appendChild(div);
}
function removeServer(server) {
    var serverBody = document.getElementById('serverBody');
    if(serverBody.childElementCount > 1) {
        serverBody.removeChild(serverBody.lastChild);
    }
}
document.getElementById("addServer").addEventListener("click", () => addServer());
document.getElementById("removeServer").addEventListener("click", () => removeServer());
document.addEventListener("DOMContentLoaded", restoreOptions);
document.querySelector("form").addEventListener("submit", saveOptions);
