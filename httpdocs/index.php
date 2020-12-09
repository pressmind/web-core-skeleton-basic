<?php
include_once '../application/bootstrap.php';

$search = new Pressmind\Search(
    [
        'visibility' => \Pressmind\Search\Condition\Visibility::create([10, 30]),
        'priceRange' => \Pressmind\Search\Condition\PriceRange::create(1, 100000)
    ]
);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="assets/vendor/bootstrap-4.5.3-dist/css/bootstrap.min.css">
    <title>pressmind SDK - Example for a simple list of media objects with detail link</title>
</head>
<body>
<div class="container">
    <div class="list-group">
    <?php foreach ($search->getResults() as $mediaObject) {?>
        <div class="list-group-item">
            <div class="row">
                <div class="col-2">

                </div>
                <div class="col-10">
                    <a href="detail.php?id=<?php echo $mediaObject->getId();?>"><?php echo $mediaObject->name;?></a> <span class="badge badge-success float-right"> ab <?php echo number_format($mediaObject->getCheapestPrice()->price_total, 2, ',', '.');?> EUR</span>
                </div>
            </div>
        </div>
    <?php } ?>
    </div>
</div>
</body>
</html>
