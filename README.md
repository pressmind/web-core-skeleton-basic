# pressmind® web-core

This is a simple skeleton web application using the pressmind SDK. It is intended to give you a clean starting point for creating web applications or RESTFULL Services using the pressmind® PIM-System

##  System Requirements
* PHP 7.*
* MySQL or MariaDB
* Composer
* PHP-Extensions:
    * ext-imagick or ext-gd (for best image quality ext-imagick is recommended)
    * ext-json
    * ext-curl
    * ext-bcmath
    * ext-pdo
    * ext-mbstring
    * ext-readline
* a pressmind® License ;-)

It is recommended to install the software on a appropriate operating system. 
Linux is highly recommended, for developing purposes Mac OS X might work.  
*A local installation on Microsoft Windows in a system like XAMPP is not supported and this not recommended. 
For local development on Windows please consider using a virtual environment like VirtualBox, Windows Subsystem for Linux or Docker*


### Recommended additional server infrastructure
* redis-server
* imagick
* cwebp
* jpegoptim
* amazon s3 bucket for image storage
* transparent cdn with amazon s3 support


### pressmind® API Credentials
You need a pressmind® REST API access. (api key, user, assword)  
Ask your pressmind® Integration-Manager.

## Quickstart

This quickstart is aimed towards an experienced audience.  
You should have at least an intermediate knowledge in MySQL, PHP-Programming and webserver-administration to get the application running as intended.

* [Installation](quickstart/installation.md)
* [Understanding pressmind anatomy](quickstart/wtf_pressmind.md)
* [Configuration options](quickstart/config.md)
* [Searching and list media objects](quickstart/search.md)
* [Building Search Filters](quickstart/search_filter.md)
* [Initializing the rest server](quickstart/rest_server_initialize.md)
* [Custom Import Hooks](quickstart/custom_import_hooks.md)
* [Custom SQL Queries](quickstart/custom_sql_queries.md)
* [Writing Export Scripts](quickstart/writing_export_scripts.md)
* [Image Handling](quickstart/image_handling.md)
* [Pretty URL](quickstart/pretty_url.md)
* [CLI Tools](quickstart/cli_tools.md)

## More 
See this [TravelShop Theme](https://github.com/pressmind/wp-travelshop-theme) 
for a working WordPress implementation of this pressmind SDK sample application.