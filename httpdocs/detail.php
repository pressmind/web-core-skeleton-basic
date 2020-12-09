<?php
error_reporting(1);
ini_set('display_errors', 1);
use Pressmind\ORM\Object\MediaObject;

require_once '../application/bootstrap.php';
$mediaObject = new MediaObject(intval($_GET['id']), true);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="assets/vendor/bootstrap-4.5.3-dist/css/bootstrap.min.css">
    <style>
        pre {
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
    echo $mediaObject->render('example');

    ?>
</div>
<script src="assets/vendor/jquery/jquery-3.5.1.min.js"></script>
<script src="assets/vendor/popper/popper-1.16.0.min.js"></script>
<script src="assets/vendor/bootstrap-4.5.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
