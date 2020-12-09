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
    <pre>
        <?php print_r($mediaObject->getLog());?>
    </pre>
    <pre>
        <?php print_r($mediaObject->toStdClass());?>
    </pre>
    <?php
    //To Render a Media Object, we just need to call the Render function and give the suffix of the render script as parameter
    //In this case the view script with the naming convention <MediaObjectType>_Example.php  (e.g. Reise_Example.php) will be called and rendered.
    //The example view scripts for all media object types can be found in /application/Custom/Views after the install.php script has been executed.
    echo $mediaObject->render('example');

    ?>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>
