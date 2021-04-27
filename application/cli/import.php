<?php
namespace Pressmind;

use Exception;
use Pressmind\Import\DataView;
use Pressmind\Log\Writer;
use Pressmind\ORM\Object\MediaObject;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

$args = $argv;
$args[1] = isset($argv[1]) ? $argv[1] : null;

switch ($args[1]) {
    case 'fullimport':
        $importer = new Import('fullimport');
        Writer::write('Importing all media objects', Writer::OUTPUT_BOTH, 'import', Writer::TYPE_INFO);
        $object_type = null;
        $visibilities = null;
        if(isset($args[2]) && is_numeric($args[2])) {
            $object_type = [$args[2]];
            Writer::write('Import limited to object type IDs: ' . implode(',', $object_type), Writer::OUTPUT_BOTH, 'import', Writer::TYPE_INFO);
        } else if(isset($args[2]) && $args[2] == '--visibilities') {
            if(isset($args[3])) {
                $visibilities = array_map('trim', explode(',', $args[3]));
            }
        }
        try {
            $importer->import(null, $object_type, $visibilities);
            if($importer->hasErrors()) {
                echo ("WARNING: Import threw errors:\n" . implode("\n", $importer->getErrors())) . "\nSee database table pmt2core_logs or " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_error.log for details\n";
            }
            Writer::write('Import done.', Writer::OUTPUT_BOTH, 'import', Writer::TYPE_INFO);
        } catch(Exception $e) {
            Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'import', Writer::TYPE_ERROR);
            echo "WARNING: Import threw errors:\n" . $e->getMessage() . "\nSee database table pmt2core_logs or " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_error.log for details\n";
        } finally {
            //$importer->postImport();
        }
        break;
    case 'resume': {
        $importer = new Import('fullimport');
        Writer::write('Resuming import of media objects', Writer::OUTPUT_BOTH, 'import', Writer::TYPE_INFO);
        try {
            $importer->importMediaObjectsFromFolder();
            if($importer->hasErrors()) {
                echo ("WARNING: Import threw errors:\n" . implode("\n", $importer->getErrors())) . "\nSee database table pmt2core_logs or " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_error.log for details\n";
            }
            Writer::write('Import done.', Writer::OUTPUT_BOTH, 'import', Writer::TYPE_INFO);
        } catch(Exception $e) {
            Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'import', Writer::TYPE_ERROR);
            echo "WARNING: Import threw errors:\n" . $e->getMessage() . "\nSee database table pmt2core_logs or " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_error.log for details\n";
        } finally {
            //$importer->postImport();
        }
    }
    case 'dataview':
        $importer = new DataView();
        $importer->import();
        break;
    case 'mediaobject':
        if(!empty($args[2])) {
            $importer = new Import('mediaobject');
            Writer::write('Importing mediaobject ID(s): ' . $args[2], Writer::OUTPUT_BOTH, 'import', Writer::TYPE_INFO);
            $ids = array_map('trim', explode(',', $args[2]));
            try {
                $importer->importMediaObjectsFromArray($ids);
                Writer::write('Import done.', Writer::OUTPUT_BOTH, 'import', Writer::TYPE_INFO);
                //$importer->postImport();
                if($importer->hasErrors()) {
                    echo ("WARNING: Import threw errors:\n" . implode("\n", $importer->getErrors())) . "\nSee database table pmt2core_logs or " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_errors.log for details\n";
                }
            } catch(Exception $e) {
                Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'import', Writer::TYPE_ERROR);
                echo "WARNING: Import threw errors:\n" . $e->getMessage() . "\nSee database table pmt2core_logs or " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_error.log for details\n";
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
                echo "WARNING: Import threw errors:\n" . $e->getMessage() . "\nSee database table pmt2core_logs or " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_error.log for details\n";
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
                    echo ("WARNING: Import threw errors:\n" . implode("\n", $importer->getErrors())) . "\nSee database table pmt2core_logs or " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_error.log for details\n";
                }
            } catch(Exception $e) {
                Writer::write($e->getMessage(), Writer::OUTPUT_BOTH, 'import', Writer::TYPE_ERROR);
                echo "WARNING: Import threw errors:\n" . $e->getMessage() . "\nSee database table pmt2core_logs or " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_error.log for details\n";
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
                    echo "WARNING: Depublish for id " . $id . "  failed:\n" . $e->getMessage() . "\nSee database table pmt2core_logs or " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_error.log for details\n";
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
                    echo "WARNING: Destruction for mediaobject " . $id . "  failed:\n" . $e->getMessage() . "\nSee database table pmt2core_logs or " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_error.log for details\n";
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
            echo "WARNING: Import threw errors:\n" . $e->getMessage() . "\nSee database table pmt2core_logs or " . Writer::getLogFilePath() . DIRECTORY_SEPARATOR . "import_error.log for details\n";
        }
        break;
    case 'insurances':
        $importer = new Import('insurances');
        break;
    case 'help':
    case '--help':
    case '-h':
    default:
        $helptext = "usage: import.php [fullimport | mediaobject | itinerary | objecttypes | insurances | depublish | destroy | remove_orphans] [<single id or commaseparated list of ids>]\n";
        $helptext .= "Example usages:\n";
        $helptext .= "php import.php fullimport (import all media objects that are allowed by configuration)\n";
        $helptext .= "php import.php mediaobject 123456, 7890124 (import only the given media objects)\n";
        $helptext .= "php import.php objecttypes 123, 456 (import only this media object types)\n";
        $helptext .= "php import.php itinerary 123456 (imports itinerary by given media objects)\n";
        $helptext .= "php import.php destroy 123456,12345 (removes given media objects from database) \n";
        $helptext .= "php import.php depublish 123456,12345 (set visibility to 'nobody')\n";
        $helptext .= "php import.php remove_orphans (removes orphans from database)";
        $helptext .= "php import.php insurances (import insurances)";
        echo $helptext;
}

