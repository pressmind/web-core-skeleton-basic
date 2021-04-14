# CLI tools 
This package provides several command line tools for interacting with the pressmind sdk.

All tools are located in cli/

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

## CLI tools explained:

### install.php
Install routine (creates models, creates initial config.json)
```shell script
cd web-core/application/cli
php install.php
```

| Parameter    | Description    |
|---           |---             |
| no_update    |                |
| with_static  | creates tables with static data like zip, airports, airlines, etc. Per default, these tables are not created.|
| only_static  | installs only the static data |

### import.php


Example 1: runs a fullimport
```shell script
cd web-core/application/cli
php import.php fullimport
```

Example 2: imports only defined media objects
```shell script
cd web-core/application/cli
php import.php mediaobject 12345,12345
```

Example 3: imports only defined media object types
```shell script
cd web-core/application/cli
php import.php objecttypes 124,145
```

Example 4: imports only insurances
```shell script
cd web-core/application/cli
php import.php insurances
```

Example 5: removes orphans (In case, media object(s) exists in web-core database but no longer in pressmind.)
```shell script
cd web-core/application/cli
php import.php remove_orphans
```

Example 6: removes media objects from web-core database
```shell script
cd web-core/application/cli
php import.php destroy 12345,123456
```

Example 7: sets visibility to nobody (not recommended, because this creates inconsistent media objects)
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
| fullimport        | -              |imports all media objects that are allowed by configuration|
| mediaobject       | 12345,1234     |imports only the given media objects|
| dataview          | -              ||
| itinerary         | 12345,1234     |imports itinerary by given media objects|
| objecttypes       | 122,123        |imports only this media object types|
| depublish         | 12345,1234     |sets visibility to 'nobody'|
| destroy           | 12345,1234     |removes given media objects from database|
| remove_orphans    | -              |removes orphans from database|
| insurances        | -              |imports insurances|
| help              | -              ||

### cron.php
Run scheduled tasks like cache management and logfile cleanup.
Scheduled tasks can be configured in config.json. 

No parameters expected.

Example: run manually
```shell script
cd web-core/application/cli
php cron.php
```

Add this command to your cron tab. See [Installation Documentation](installation.md#3-configure-crontab) for the correct setup of crontab.

### rebuild_cache.php
Rebuild or warm up the media object cache. 

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
This routine checks your database against the current pressmind object model. 
It detects changes and asks you to fix them. 

No parameters expected.

Example:
```shell script
cd web-core/application/cli
php integrity_check.php
```

### fulltext_indexer.php
This script builds the fulltext index based on the given configuration options 
([ENV].data.media_types_fulltext_index_fields.*). If you change the configuration options 
you have to rebuild the fulltext index with this tool.


Example 1: rebuilds the index for all media objects
```shell script
cd web-core/application/cli
php fulltext_indexer.php
```

Example 2: rebuilds the index only for defined media objects
```shell script
cd web-core/application/cli
php fulltext_indexer.php 12354,12346
```

### image_processor.php
This script runs trough the table pmt2core_media_object_images and
downloads and processes all image derivates and stores them on the defined storage.
This script is a subpart of the import process and will be triggered
automatically during import process. A manual run is usually not necessary.

No parameters expected.

Example:
```shell script
cd web-core/application/cli
php image_processor.php
```

### file_downloader.php
This script runs trough the table pmt2core_media_object_files and
downloads all required files (media object attachments)
to your given location. This script is a subpart of the import process and will be triggered
automatically during import process. A manual run is usually not necessary.

No parameters expected.

Example:
```shell script
cd web-core/application/cli
php integrity_check.php
```




