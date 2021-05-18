# Web App with Race Condition Vulnerabilities

## Quick start

### Using docker

1. Add `127.0.0.1 php-docker.local` to your `/etc/hosts`
2. Go to `/raceocat/vuln-webapp/docker/` and execute:

```
docker-compose up -d
```

* `http://php-docker.local:80/index.php` - to access the web app
* `http://localhost:8000` - phpmyadmin, login using `root`/`qwerty` credentials and the host is `mariadb`
* `/docker/init.sql` - the DB dump which is imported
