<?php
namespace Pressmind;

use Exception;
use Pressmind\Log\Writer;
use Pressmind\ORM\Object\MediaObject;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

$args = $argv;
$args[1] = isset($argv[1]) ? $argv[1] : null;

switch ($args[1]) {
    case 'fullimport':
        $importer = new Import('fullimport');
        Writer::write('Importing all media objects', Writer::OUTPUT_BOTH, 'import', Writer::TYPE_INFO);
        try {
            $importer->import();
            if($importer->hasErrors()) {
                echo ("WARNING: Import threw errors:\n" . implode("\n", $importer->getErrors())) . "\nSEE " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_error.log for details\n";
            }
            Writer::write('Import done.', Writer::OUTPUT_BOTH, 'import', Writer::TYPE_INFO);
        } catch(Exception $e) {
            Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'import', Writer::TYPE_ERROR);
            echo "WARNING: Import threw errors:\n" . $e->getMessage() . "\nSEE " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_error.log for details\n";
        } finally {
            $importer->postImport();
        }
        break;
    case 'mediaobject':
        if(!empty($args[2])) {
            $importer = new Import('mediaobject');
            Writer::write('Importing mediaobject ID(s): ' . $args[2], Writer::OUTPUT_BOTH, 'import', Writer::TYPE_INFO);
            $ids = array_map('trim', explode(',', $args[2]));
            try {
                $importer->importMediaObjectsFromArray($ids);
                Writer::write('Import done.', Writer::OUTPUT_BOTH, 'import', Writer::TYPE_INFO);
                $importer->postImport();
                if($importer->hasErrors()) {
                    echo ("WARNING: Import threw errors:\n" . implode("\n", $importer->getErrors())) . "\nSEE " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_errors.log for details\n";
                }
            } catch(Exception $e) {
                Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'import', Writer::TYPE_ERROR);
                echo "WARNING: Import threw errors:\n" . $e->getMessage() . "\nSEE " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_error.log for details\n";
            }
        } else {
            echo "Missing mediaobject id(s)";
        }
        break;
    case 'itinerary':
        if(!empty($args[2])) {
            $importer = new Import('itinerary');
            Writer::write('Importing itinerary for Media Object ID(s): ' . $args[2], Writer::OUTPUT_BOTH, 'import', Writer::TYPE_INFO);
            $ids = array_map('trim', explode(',', $args[2]));
            try {
                foreach ($ids as $id) {
                    $importer->importItinerary($id);
                }
            } catch (Exception $e) {
                Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'import', Writer::TYPE_ERROR);
                echo "WARNING: Import threw errors:\n" . $e->getMessage() . "\nSEE " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_error.log for details\n";
            }
        } else {
            echo "Missing mediaobject id(s)";
        }
        break;
    case 'objecttypes':
        if(!empty($args[2])) {
            $importer = new Import('objecttypes');
            Writer::write('Importing objecttypes ID(s): ' . $args[2], Writer::OUTPUT_BOTH, 'import', Writer::TYPE_INFO);
            $ids = array_map('trim', explode(',', $args[2]));
            try {
                $importer->importMediaObjectTypes($ids);
                Writer::write('Import done.', Writer::OUTPUT_BOTH, 'import', Writer::TYPE_INFO);
                if($importer->hasErrors()) {
                    echo ("WARNING: Import threw errors:\n" . implode("\n", $importer->getErrors())) . "\nSEE " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_error.log for details\n";
                }
            } catch(Exception $e) {
                Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'import', Writer::TYPE_ERROR);
                echo "WARNING: Import threw errors:\n" . $e->getMessage() . "\nSEE " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_error.log for details\n";
            }
        } else {
            echo "Missing objecttype id(s)";
        }
        break;
    case 'depublish':
        if(!empty($args[2])) {
            Writer::write('Depublishing mediaobject ID(s): ' . $args[2], Writer::OUTPUT_BOTH, 'import', Writer::TYPE_INFO);
            $ids = array_map('trim', explode(',', $args[2]));
            foreach ($ids as $id) {
                try {
                    $media_object = new MediaObject($id);
                    $media_object->visibility = 10;
                    $media_object->update();
                    Writer::write('Mediaobject ' . $id . ' successfully depublished (visibility set to 10/nobody)', Writer::OUTPUT_BOTH, 'import', Writer::TYPE_INFO);
                } catch (Exception $e) {
                    Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'import', Writer::TYPE_ERROR);
                    echo "WARNING: Depublish for id " . $id . "  failed:\n" . $e->getMessage() . "\nSEE " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_error.log for details\n";
                }
            }
        }
        break;
    case 'destroy':
        if(!empty($args[2])) {
            Writer::write('Destroying mediaobject ID(s): ' . $args[2], Writer::OUTPUT_BOTH, 'import', Writer::TYPE_INFO);
            $ids = array_map('trim', explode(',', $args[2]));
            foreach ($ids as $id) {
                try {
                    $media_object = new MediaObject($id);
                    $media_object->delete();
                    Writer::write('Mediaobject ' . $id . ' successfully destroyed', Writer::OUTPUT_BOTH, 'import', Writer::TYPE_INFO);
                } catch (Exception $e) {
                    Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'import', Writer::TYPE_ERROR);
                    echo "WARNING: Destruction for mediaobject " . $id . "  failed:\n" . $e->getMessage() . "\nSEE " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_error.log for details\n";
                }
            }
        }
        break;
    case 'remove_orphans':
        $importer = new Import('remove_orphans');
        Writer::write('Removing orphans from database', Writer::OUTPUT_BOTH, 'import', Writer::TYPE_INFO);
        try {
            $importer->removeOrphans();
        } catch(Exception $e) {
            Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'import', Writer::TYPE_ERROR);
            echo "WARNING: Import threw errors:\n" . $e->getMessage() . "\nSEE " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_error.log for details\n";
        }
        break;
    case 'help':
    case '--help':
    case '-h':
    default:
        $helptext = "usage: import.php [fullimport | mediaobject | itinerary | objecttypes] [<single id or commaseparated list of ids>]\n";
        $helptext .= "Example usages:\n";
        $helptext .= "php import.php fullimport\n";
        $helptext .= "php import.php mediaobject 123456, 78901234 <single or multiple ids allowed>\n";
        $helptext .= "php import.php objecttypes 123, 456 <singe or multiple ids allowed>\n";
        $helptext .= "php import.php itinerary 123456 <singe or multiple ids allowed>\n";
        echo $helptext;
}

