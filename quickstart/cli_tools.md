# CLI Tools 
This package brings several commandline tools for interacting with the pressmind sdk.

All of this tools are located in cli/

## Overview

* [install.php](#installphp)
* [import.php](#importphp)
* [cron.php](#cronphp)
* [rebuild_cache.php](#rebuild_cachephp)
* [rebuild_routes.php](#rebuild_routesphp)
* [integrity_check.php](#integrity_checkphp)
* [fulltext_indexer.php](#fulltext_indexerphp)
* [image_processor.php](#image_processorphp)
* [file_downloader.php](#file_downloaderphp)

## CLI Tools explained:

### install.php
Installroutine (creates models, creates initial config.json)
```shell script
cd web-core/application/cli
php install.php
```

| Parameter    | Description    |
|---           |---             |
| no_update    |                |
| with_static  | Creates tables with static data like zip, airports, airlines, etc. Per default this tables are not created.|
| only_static  | Installs only the static data |

### import.php


Example 1: run a fullimport
```shell script
cd web-core/application/cli
php import.php fullimport
```

Example 2: import only defined media objects
```shell script
cd web-core/application/cli
php import.php mediaobject 12345,12345
```

Example 3: import only defined media objects types
```shell script
cd web-core/application/cli
php import.php objecttypes 124,145
```

Example 4: import only insurances
```shell script
cd web-core/application/cli
php import.php insurances
```

Example 5: remove orphans (if media object(s) exists in web-core database, but no more in pressmind)
```shell script
cd web-core/application/cli
php import.php remove_orphans
```

Example 6: remove media objects from web-core database
```shell script
cd web-core/application/cli
php import.php destroy 12345,123456
```

Example 7: set visibility to nobody (not recommend, because this creates inconsistent media objects)
```shell script
cd web-core/application/cli
php import.php depublish 12345,123456
```

Example 8: imports media objects itinerary by given media object ids
```shell script
cd web-core/application/cli
php import.php itinerary 12345,123456
```

| Parameter         | Example value  |Description|
|---                |---             |---|
| fullimport        | -              |import all media objects that are allowed by configuration|
| mediaobject       | 12345,1234     |import only the given media objects|
| dataview          | -              ||
| itinerary         | 12345,1234     |imports itinerary by given media objects|
| objecttypes       | 122,123        |import only this media object types|
| depublish         | 12345,1234     |set visibility to 'nobody')|
| destroy           | 12345,1234     |removes given media objects from database|
| remove_orphans    | -              |removes orphans from database)|
| insurances        | -              |import insurances|
| help              | -              ||

### cron.php
Run scheduled tasks like cache management and logfile cleanup.
Scheduled Tasks can configured in config.json. 

No parameters expected.

Example: run manually
```shell script
cd web-core/application/cli
php cron.php
```

Add this command to you're cron tab. See [Installation Documentation](installation.md#3-configure-crontab) for the correct setup of crontab.

### rebuild_cache.php
Rebuild or warmup the media object cache. 

No parameters expected.

Example:
```shell script
cd web-core/application/cli
php rebuild_cache.php
```

### rebuild_routes.php
Rebuild URL routes. 
If you change route specific configuration options ([ENV].data.media_types_pretty_url.*), 
you have to rebuild the routes. The routes are stored in the table pmt2core_routes.

No parameters expected.

Example:
```shell script
cd web-core/application/cli
php rebuild_routes.php
```

### integrity_check.php
This routine checks you're database against the current pressmind object model. 
It detect changes and ask you fix them. 

No parameters expected.

Example:
```shell script
cd web-core/application/cli
php integrity_check.php
```

### fulltext_indexer.php
This script build the fulltext index based on the given configuration options 
([ENV].data.media_types_fulltext_index_fields.*). If you change the configuration options 
you have to rebuild the fulltext index with this tool.


Example 1: rebuild the index for all media objects
```shell script
cd web-core/application/cli
php fulltext_indexer.php
```

Example 2: rebuild index only for defined media objects
```shell script
cd web-core/application/cli
php fulltext_indexer.php 12354,12346
```

### image_processor.php
This script run's trough the table pmt2core_media_object_images and
download and processes all image derivates and stores them on the defined storage.
This script is a subpart of the import process and will be triggered
automatically during import process. Usually a manual run is not necessary.

No parameters expected.

Example:
```shell script
cd web-core/application/cli
php image_processor.php
```

### file_downloader.php
This script run's trough the table pmt2core_media_object_files and
downloads all required files (media object attachments)
to you're given location. This script is a subpart of the import process and will be triggered
automatically during import process. Usually a manual run is not necessary.

No parameters expected.

Example:
```shell script
cd web-core/application/cli
php integrity_check.php
```




