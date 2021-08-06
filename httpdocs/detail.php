<?php
error_reporting(1);
ini_set('display_errors', 1);

use Pressmind\ORM\Object\MediaObject;

$start_time = microtime(true);
require_once '../application/bootstrap.php';
$skipCache = isset($_GET['preview']);
$mediaObject = new MediaObject(intval($_GET['id']), false, $skipCache);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="assets/vendor/bootstrap-4.5.3-dist/css/bootstrap.min.css">
    <style>
        pre {
            border: 1px solid #c1c1c1;
            border-radius: 5px;
            padding: 15px;
            max-height: 400px;
            overflow: auto;
        }
    </style>
    <title>pressmind SDK - An example detail page for media objects</title>
</head>
<body>
<div class="container">
    <?php
    //To Render a Media Object, we just need to call the Render function and give the suffix of the render script as parameter
    //In this case the view script with the naming convention <MediaObjectType>_Example.php  (e.g. Reise_Example.php) will be called and rendered.
    //The example view scripts for all media object types can be found in /application/Custom/Views after the install.php script has been executed.
    try {
        echo $mediaObject->render('example', null, ['my_custom_key' => 'this is custom data for the render script', 'second_custom_key' => 'this data can be of any kind (string, array, object)']);
    } catch(Exception $e) {
        echo "Failed to render Media Object";
    } ?>
    <?php $end_time = microtime(true);?>
    <h4>Total Rendertime:</h4>
    <pre><?php echo ($end_time - $start_time);?> seconds</pre>
    <h4>Total Memory Usage</h4>
    <pre><?php echo(memory_get_usage(true) / (1024*1024));?> MB</pre>
</div>
<script src="assets/vendor/jquery/jquery-3.5.1.min.js"></script>
<script src="assets/vendor/popper/popper-1.16.0.min.js"></script>
<script src="assets/vendor/bootstrap-4.5.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
