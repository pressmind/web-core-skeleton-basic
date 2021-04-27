# Writing custom SQL queries
In some cases it is necessary to write a custom SQL query.
But be aware that the frameworks business logic is much safer!

```php
<?php
include_once 'bootstrap.php';
$db = \Pressmind\Registry::getInstance()->get('db');
$sql = "SELECT * FROM pmt2core_media_objects";
$r = $db->fetchAll($sql);
print_r($r);
```
