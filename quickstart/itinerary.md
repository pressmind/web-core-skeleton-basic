# Building itineraries
Depending on the pressmind media object model, it is possible to build detailed itineraries. 

An itinerary contains descriptions (and variants), images, geolocations and some additional information
for each day or step.

Depending on how the itineraries are set up in pressmind, there are two ways to display the itineraries:  
* with getItineraryVariant()
* with getItinerarySteps()

### Example itinerary (with Variants):
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
                        echo '<img src="' . $image->getUri($derivative_name) . '" ' . $image->getSizes($derivative_name) . '>';
                    }
                }
                echo '<h2>Linked Text Objects</h2>';
                foreach ($step->text_media_objects as $text_media_object) {
                     echo '<pre>' . print_r($text_media_object->toStdClass(), true) . '</pre>';
                }
            }
        }
    }
} catch (Exception $e) {
    echo '<h1>' . $e->getMessage() . '</h1>';
}
````

### Example itinerary (with simple Steps):
````php

namespace Pressmind;
use Exception;
use Pressmind\ORM\Object\Itinerary\Step;
use Pressmind\ORM\Object\MediaObject;

include_once 'bootstrap.php';

$config = Registry::getInstance()->get('config');

try {
    $mediaObject = new MediaObject((int)$_GET['id']);
    /** @var Step $step */
    foreach ($mediaObject->getItinerarySteps() as $step) {
        $content = $step->getContentForlanguage();
        echo('<h1>' . $content->headline . '</h1>');
        echo($content->description);
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
                echo '<img src="' . $image->getUri($derivative_name) . '" ' . $image->getSizes($derivative_name) . '>';
            }
        }
        echo '<h2>Linked Text Objects</h2>';
        foreach ($step->text_media_objects as $text_media_object) {
             echo '<pre>' . print_r($text_media_object->toStdClass(), true) . '</pre>';
        }
    }
} catch (Exception $e) {
    echo '<h1>' . $e->getMessage() . '</h1>';
}
````
