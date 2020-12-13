# Raceocat - Browser extension to test for race conditions in web applications.

- Monitoring live requests
- Editing headers on live requests
- Canceling live requests
- Redirecting live requests

## Usage

### Monitoring live requests

Once installed you will see a blue cloud button in the toolbar, to the left of the URL bar. Click that to start monitoring requests.

### Configuring Dispatcher

Once you have installed the add-on to your browser you navigate to [about:addons](about:addons), search for the add-on and click on `Preferences`. There you can enter your command-and-control server used by the dispatcher.
 
![Add-On Preferences](./docs/Add-On%20Preferences.png)
 
## Install

### Install from the Official Add-ons Page

You can install from the official add-ons page here, [here](tbd).

### Development: Installing from source in Firefox

1. Download and unpack the source code.
2. Navigate to [about:debugging](about:debugging).
3. Click the button to add a temporary extension.
4. Select the manifest.json file and hit OK.

It will automatically uninstall when you close the browser.
