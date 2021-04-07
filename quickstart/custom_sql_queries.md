# Writing custom sql queries
In some cases it's neccesry to write a custom sql query.
But be aware the frameworks business logic is much safer!

```php
<?php
include_once 'bootstrap.php';
$db = \Pressmind\Registry::getInstance()->get('db');
$sql = "SELECT * FROM pmt2core_media_objects";
$r = $db->fetchAll($sql);
print_r($r);
```