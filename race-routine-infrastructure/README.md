# Race Routine Infrastructure

## Prerequisites

- PHP Webserver / Webspace

## Usage

### Parameters

| Parameter | Required? | Description | Example Values | 
| :------------- | :------------- | :------------- | :------------- | 
| payload | required | The HTTP request which is beeing called  | `{"method":"GET","url":"https://localhost:443/log.php"}` | 
| server | at least one item required | A **comma seperated list** of servers which are parallel called by the dispatcher | `https://host1/racer.php,https://host2/racer.php,https://host3/racer.php` | 
| proxy | optional | A proxy which is used by the `racer.php` while requesting your payload. Not recommended because the proxy is affecting your speed. | `localhost:8080` | 


### Dispatcher Script

Distributes the request (payload) to the given list of servers. 

```
dispatcher.php?payload=PAYLOAD&proxy=PROXY&server=SERVER
```

### Race Script "Racer"

Sends a huge amount of requests to the given payload. Requests are perfomed in an optimized way to get the best speed. 

```
racer.php?payload=PAYLOAD&proxy=PROXY
```
