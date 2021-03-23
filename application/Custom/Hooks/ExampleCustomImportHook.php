<?php


namespace Custom\Hooks;


use Pressmind\HelperFunctions;
use Pressmind\Import\ImportInterface;
use Pressmind\ORM\Object\MediaObject;
use Pressmind\Registry;

/**
 * Class ExampleCustomImportHook
 * @package Custom\Hooks
 * To invoke this class during import, add the following option to your configuration
 * (the key 123 is a placeholder for the desired media object type ID):
 * {
 *   "development" : {
 *     ...
 *     "media_type_custom_import_hooks": {
 *       "123": [
 *         "Custom\\Hooks\\ExampleCustomImportHook",
 *       ]
 *     },
 *   },
 *   ......
 * }
 */
class ExampleCustomImportHook implements ImportInterface
{

    /**
     * @var MediaObject
     */
    private $_mediaObject;

    /**
     * @var array
     */
    private $_errors = [];

    /**
     * @var array
     */
    private $_log = [];

    /**
     * ExampleCustomImportHook constructor.
     * @param integer $id_media_object
     */
    public function __construct($id_media_object)
    {
        $this->_log[] = 'Custom Import for media object ' . $id_media_object . ' invoked';
        $this->_mediaObject = new MediaObject($id_media_object, true);
    }

    /**
     * @return void
     */
    public function import()
    {
        $config = Registry::getInstance()->get('config');
        $log_path = HelperFunctions::replaceConstantsFromConfig($config['logging']['log_file_path']);
        $this->_log[] = 'Writing JSON representatzion of media object to ' . $log_path;
        try {
            $json = $this->_mediaObject->toJson();
            if(!file_put_contents($log_path . '/' . $this->_mediaObject->id . '.json', $json)) {
                $this->_errors[] = 'Failed to write to' . $log_path;
            }
        } catch (\Exception $e) {
            $this->_errors[] = $e->getMessage();
        }
        unset($this->_mediaObject);
    }

    /**
     * @return array
     */
    public function getLog()
    {
       return $this->_log;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

}
