# Custom Import Hooks

In some cases is neccessary to run hooks during the import process between 
pressmind and the sdk/web-core. 

there possible trigger to run a hook during import process:
* Option A: if a defined my.Content datasource is mapped to the current media object
* Option B: by a defined media object type

## Option A: by a defined datasource

Example configuration in config.json:
```json
"data": {
    "touristic": {
      "my_content_class_map": { 
        "192": "\\Custom\\MyTouristicImport"
      }
    },
}
```
Example CustomImportClass
```php
namespace Custom;

use DateTime;
use Exception;
use Pressmind\Import\ImportInterface;
use Pressmind\ORM\Object\MediaObject;
use Pressmind\ORM\Object\Touristic;
use stdClass;

/**
 * @package Custom
 */
class MyTouristicImport implements ImportInterface
{

    /**
     * @var array
     */
    private $_log = [];

    /**
     * @var array
     */
    private $_errors = [];

    /**
     * @var stdClass
     */
    private $_data;

    /**
     * @var integer
     */
    private $_id_media_object;

    /**
     * @var MediaObject
     */
    private $_media_object;


    /**
     * @param stdClass $data contains my.Content datasource join object
     * @param integer $id_media_object
     * @throws Exception
     */
    public function __construct($data, $id_media_object)
    {
        $this->_data = $data;
        $this->_id_media_object = $id_media_object;
        $this->_media_object = new MediaObject($this->_id_media_object, true);
    }


    /**
     * @return void
     * @throws Exception
     */
    public function import() {

        $this->_log[] = '\Custom\\'.__CLASS__.': Starting import of external touristic data for media object ID: ' . $this->_id_media_object;

        // Build your own booking package from here.
        
        echo 'foreign id is here:'.$this->_data->import_id; // this id is set by my.Content trough pressmind UI
        
        $json = file_get_contents('https://www.your-service.de/?id='.$this->_data->import_id)
        
        // for example, load data from you're datasource, and map it to Touristic\Booking\Package
        $booking_package = new Touristic\Booking\Package();
        $booking_package->fromJson($json);
        
        // you have to generate UUID for each object, the're is deliberately no autoincrement from here
        //$booking_package->id_booking_package = $this->generateUuid(); 
       
        try {
            // insert
            $booking_package->create();

        } catch (Exception $e) {
            $this->_log[] = '\Custom\CustomTouristicImportExample: Error occurred!';
            $this->_errors[] = $e->getMessage();
        }

        $this->_log[] = '\Custom\\'.__CLASS__.': Import of external data for media object ID:' . $this->_id_media_object . ' done';
        return;
    }

    public function generateUuid($salt = null){
        return md5($salt .'-'.microtime().'-'.random_bytes(16));
    }

    public function getLog()
    {
        return $this->_log;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

}

``


## Option B: by code

Example configuration in config.json:
```json
"data": {
    
     "media_type_custom_import_hooks": {
        "245": [
          "Custom\\MyTouristicImport"
        ]
      },
      
}
```
Example CustomImportClass
```php
namespace Custom;

use DateTime;
use Exception;
use Pressmind\Import\ImportInterface;
use Pressmind\ORM\Object\MediaObject;
use Pressmind\ORM\Object\Touristic;
use stdClass;

/**
 * Example: https://github.com/pressmind/web-core-skeleton-basic/blob/master/application/Custom/Hooks/ExampleCustomImportHook.php
 * @package Custom
 */
class MyTouristicImport implements ImportInterface
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
     * @throws Exception
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

    public function getLog()
    {
        return $this->_log;
    }


    public function getErrors()
    {
        return $this->_errors;
    }


}

``