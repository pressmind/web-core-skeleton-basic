<?php
namespace Pressmind;

use Exception;
use ImagickException;
use Pressmind\Image\Processor\Adapter\Factory;
use Pressmind\Image\Processor\Config;
use Pressmind\Log\Writer;
use Pressmind\ORM\Object\Itinerary\Step\DocumentMediaObject;
use Pressmind\ORM\Object\MediaObject\DataType\Picture;

if(php_sapi_name() == 'cli') {
    putenv('ENV=DEVELOPMENT');
}

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';


$config = Registry::getInstance()->get('config');

$logtext = 'Image processor started';

if(isset($argv[1]) && is_numeric($argv[1])) {
    $logtext = 'Image processor for media_object_id: ' . $argv[1] . ' started';
}

Writer::write($logtext, WRITER::OUTPUT_FILE, 'image_processor', Writer::TYPE_INFO);

try {
    $params = ['download_successful' => 0];
    if(isset($argv[1]) && is_numeric($argv[1])) {
        $params['id_media_object'] = $argv[1];
    }
    /** @var Picture[] $result */
    $result = Picture::listAll($params);
} catch (Exception $e) {
    Writer::write($e->getMessage(), WRITER::OUTPUT_FILE, 'image_processor', Writer::TYPE_ERROR);
}

Writer::write('Processing ' . count($result) . ' images', WRITER::OUTPUT_FILE, 'image_processor', Writer::TYPE_INFO);

foreach ($result as $image) {
    $binary_image = null;
    Writer::write('Processing image ID:' . $image->getId(), WRITER::OUTPUT_FILE, 'image_processor', Writer::TYPE_INFO);
    Writer::write('ID ' . $image->getId() . ': Downloading image from ' . $image->tmp_url, WRITER::OUTPUT_FILE, 'image_processor', Writer::TYPE_INFO);
    try {
        $binary_image = $image->downloadOriginal();
    } catch (Exception $e) {
        Writer::write('ID ' . $image->getId() . ': ' . $e->getMessage(), WRITER::OUTPUT_FILE, 'image_processor', Writer::TYPE_ERROR);
        continue;
    }
    $imageProcessor = Factory::create($config['image_handling']['processor']['adapter']);
    Writer::write('ID ' . $image->getId() . ': Creating derivatives', WRITER::OUTPUT_FILE, 'image_processor', Writer::TYPE_INFO);
    foreach ($config['image_handling']['processor']['derivatives'] as $derivative_name => $derivative_config) {
        try {
            $processor_config = Config::create($derivative_name, $derivative_config);
            $image->createDerivative($processor_config, $imageProcessor, $binary_image);
            Writer::write('ID ' . $image->getId() . ': Processing sections', WRITER::OUTPUT_FILE, 'image_processor', Writer::TYPE_INFO);
        } catch (Exception $e) {
            Writer::write('ID ' . $image->getId() . ': ' . $e->getMessage(), WRITER::OUTPUT_FILE, 'image_processor', Writer::TYPE_ERROR);
            continue;
        }
        if(isset($image->sections)) {
            foreach ($image->sections as $section) {
                Writer::write('ID ' . $image->getId() . ': Downloading section image from ' . $section->tmp_url, WRITER::OUTPUT_FILE, 'image_processor', Writer::TYPE_INFO);
                try {
                    $binary_section_file = $section->downloadOriginal();
                    Writer::write('ID ' . $image->getId() . ': Creating section image derivatives', WRITER::OUTPUT_FILE, 'image_processor', Writer::TYPE_INFO);
                    $section->createDerivative($processor_config, $imageProcessor, $binary_section_file);
                    unset($binary_section_file);
                } catch (Exception $e) {
                    Writer::write('ID ' . $image->getId() . ': ' . $e->getMessage(), WRITER::OUTPUT_FILE, 'image_processor', Writer::TYPE_ERROR);
                    continue;
                }
            }
        }
    }
    unset($binary_image);
}

try {
    $itinerary_params = ['download_successful' => 0];
    /** @var DocumentMediaObject[] $result */
    $itinerary_result = DocumentMediaObject::listAll($itinerary_params);
} catch (Exception $e) {
    Writer::write($e->getMessage(), WRITER::OUTPUT_FILE, 'image_processor', Writer::TYPE_ERROR);
}

Writer::write('Processing ' . count($result) . ' images', WRITER::OUTPUT_FILE, 'image_processor', Writer::TYPE_INFO);

foreach ($itinerary_result as $itinerary_image) {
    $itinerary_binary_image = null;
    Writer::write('Processing itinerary image ID:' . $itinerary_image->getId(), WRITER::OUTPUT_FILE, 'image_processor', Writer::TYPE_INFO);
    Writer::write('ID ' . $itinerary_image->getId() . ': Downloading itinerary image from ' . $itinerary_image->tmp_url, WRITER::OUTPUT_FILE, 'image_processor', Writer::TYPE_INFO);
    try {
        $itinerary_binary_image = $itinerary_image->downloadOriginal();
    } catch (Exception $e) {
        Writer::write('itinerary image ID ' . $itinerary_image->getId() . ': ' . $e->getMessage(), WRITER::OUTPUT_FILE, 'image_processor', Writer::TYPE_ERROR);
        continue;
    }
    $itinerary_imageProcessor = Factory::create($config['image_handling']['processor']['adapter']);
    Writer::write('ID ' . $itinerary_image->getId() . ': Creating itinerary derivatives', WRITER::OUTPUT_FILE, 'image_processor', Writer::TYPE_INFO);
    foreach ($config['image_handling']['processor']['derivatives'] as $itinerary_derivative_name => $itinerary_derivative_config) {
        try {
            $itinerary_processor_config = Config::create($itinerary_derivative_name, $itinerary_derivative_config);
            $itinerary_image->createDerivative($itinerary_processor_config, $itinerary_imageProcessor, $itinerary_binary_image);
        } catch (Exception $e) {
            Writer::write('itinerary image ID ' . $itinerary_image->getId() . ': ' . $e->getMessage(), WRITER::OUTPUT_FILE, 'image_processor', Writer::TYPE_ERROR);
            continue;
        }
    }
    unset($itinerary_binary_image);
}

Writer::write('Image processor finished', WRITER::OUTPUT_FILE, 'image_processor', Writer::TYPE_INFO);
if(isset($argv[1]) && is_numeric($argv[1])) {
    Writer::write('Updating cache', WRITER::OUTPUT_FILE, 'image_processor', Writer::TYPE_INFO);
    $mediaObject = new ORM\Object\MediaObject($argv[1], true, true);
    $mediaObject->updateCache($mediaObject->getId());
}
