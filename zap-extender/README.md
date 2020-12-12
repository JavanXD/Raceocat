# OWASP Zed Attack Proxy (ZAP) Extender

## Overview

### copy-curl-loop.js

Adds a context menu to copy a curl command for a selected request. cURL uses the `-Z` and `--next` flag to execute 25 requests parallel within one connection. 

### forward-to-race-dispatcher.js

Adds a context menu to forward the selected request to the race dispatcher. Once executed a new browser window opens.

## Setup

1. Import Script
![Race Conditions](./docs/01%20OWASP%20ZAP%20-%20Import%20Script.png)
1. Load Script as Extender
![Race Conditions](./docs/02%20OWASP%20ZAP%20-%20Load%20Script%20as%20Extender.png)
1. Enable Script
![Race Conditions](./docs/03%20OWASP%20ZAP%20-%20Enable%20Script.png)

## Usage

Open Request Context to copy curl command 
![Race Conditions](./docs/04%20OWASP%20ZAP%20-%20Open%20Request%20Context%20to%20copy%20curl%20command.png) 

Click on "Forward Request to Race Dispatcher"  
![Race Conditions](./docs/05%20OWASP%20ZAP%20-%20Forward%20Request%20to%20Race%20Dispatcher.png) 