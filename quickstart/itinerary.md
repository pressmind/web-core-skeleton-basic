#Building Itineraries
Depeding on the pressmind media object model it's possible to build detailed itineraries. 


### Example Itinerary:
````php

namespace Pressmind;
use Exception;
use Pressmind\ORM\Object\Itinerary\Variant;
use Pressmind\ORM\Object\MediaObject;

include_once 'bootstrap.php';

$config = Registry::getInstance()->get('config');

try {
    $mediaObject = new MediaObject((int)$_GET['id']);

    /** @var Variant $variant */
    foreach ($mediaObject->getItineraryVariants() as $variant) {
        foreach ($variant->steps as $step) {
            foreach ($step->sections as $section) {
                echo('<h1>' . $section->content->headline . '</h1>');
                echo($section->content->description);
                echo '<h2>Boards</h2>';
                $day = 1;
                foreach ($step->board as $board) {
                    echo '<h3>Day ' . $day . '</h3>';
                    echo '<pre>' . print_r($board->toStdClass(), true) . '</pre>';
                    $day++;
                }
                echo '<h2>Geolocations</h2>';
                foreach ($step->geopoints as $geopoint) {
                    echo '<pre>' . print_r($geopoint->toStdClass(), true) . '</pre>';
                }
                echo '<h2>Images</h2>';
                foreach ($step->document_media_objects as $image) {
                    echo '<pre>' . print_r($image->toStdClass(), true) . '</pre>';
                    foreach ($config['imageprocessor']['derivatives'] as $derivative_name => $derivative) {
                        echo '<img src="' . $image->getImageSrc($derivative_name) . '">';
                    }
                }
            }
        }
    }
} catch (Exception $e) {
    echo '<h1>' . $e->getMessage() . '</h1>';
}
````