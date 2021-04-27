# Writing time based export scripts
In some case you have to write export files in a specified period.
It is possible to use the integrated scheduler to run a custom export script...

**Step 1:**
Create the script and locate it in APPLICATION_PATH/Custom/Export/NiceCustomExport.php.

```php
<?php
namespace Custom\Export;
use Custom\MediaType\Hotel;

use Pressmind\Search;

class NiceCustomExport {

    public function export()
    {
       
        $search = new Search([
            Search\Condition\ObjectType::create(123)
        ]);

        $buffer = [];
        foreach ($search->getResults() as $mediaObject) {
            /** @var Hotel $data */
            $buffer[] = ['id' => $mediaObject->id, 'name' => $mediaObject->name];
        }
        file_put_contents('niceExport.json', json_encode($buffer));
    }
}
```
**Step 2:**
Add the export method to the scheduler.
The scheduler config is located in the config.json.
Make sure that your cron.php is running as crontab.

```json
       "scheduled_tasks": [
           {
                "name": "JSON Export",
                "class_name": "\\Custom\\Export\\NiceCustomExport",
                "schedule": {
                    "type": "Daily",
                    "time": "Fixed",
                    "value": "02:30"
                },
                "methods": [
                    {
                        "method": "export",
                        "parameters": null,
                        "position": 1
                    }
                ]
            }
        ]
```
