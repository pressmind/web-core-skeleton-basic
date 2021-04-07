# REST Server
The rest server can initialized by:

```php
<?php
require_once 'bootstrap.php';
$config = \Pressmind\Registry::getInstance()->get('config');
$server = new \Pressmind\REST\Server($config['rest']['server']['api_endpoint']);
$server->handle();
```

## REST API documentation
https://pressmind.github.io/web-core/

### How to configure
All configuration can be done in config.json.
See [Configuration Documentation](config.md) for detailed properties.