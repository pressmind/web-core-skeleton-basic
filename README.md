# pressmind® web-core Basic Skeleton Web-Application

## This is  simple skeleton web application using the pressmind SDK intended to give a starting point for creating web applications using the Pressmind PIM-System

##  System Requirements
* PHP 7.*
* MySQL or MariaDB
* Composer
* PHP-Extensions:
    * ext-imagick or ext-gd
    * ext-json
    * ext-curl
    * ext-bcmath
    * ext-pdo
    * ext-mbstring
    * ext-readline
* a pressmind® License ;-)

### pressmind® API Credentials
You need a pressmind® REST API Access. (API Key, User, Password)
Ask your pressmind® Integration-Manager.

## Quickstart

### 1. Installation
* clone or download the repository 

Cloning:
```shell script
git clone https://github.com/pressmind/web-core-skeleton-basic.git
```

when you downloaded the .zip file, extract the sources

* create a MySQL database

```shell script
mysql -u root -p;
mysql> CREATE DATABASE pressmind;
mysql> GRANT ALL ON pressmind.* TO 'my_database_user'@'localhost' IDENTIFIED BY 'my_database_password' WITH GRANT OPTION;
``` 

* on a console move to the base folder 

```shell script
cd web-core-skeleton-basic
```

* install composer dependencies

```shell script
composer install
```

* move to the application\cli directory and run the install.php script

```shell script
cd web-core-skeleton-basic/application/cli
php install.php
```

* you will be asked for some information on database and pressmind credentials

```shell script
Welcome to the initial installation of your new pressmind web-core project.
Please enter some initial configuration data.
Enter Database Host [127.0.0.1]:
Enter Database Port [3306]:
Enter Database Name: 
Enter Database Username: 
Enter Database User Password:
Enter Pressmind API Key:
Enter Pressmind API User:
Enter Pressmind API Password:
```

* This will install the necessary database tables and generate the needed model-definitions for the media object types.  
  Additionally some basic example php files that show the use of Views are generated in the folder /application/Custom/Views as well as some html files with information on the installed media object types. You can find these under httpdocs/docs/objecttypes
* once the install script is done, configure your webserver to serve files from the httpdocs folder

```apacheconfig
#Example Apache2 Configuration

ServerName web-core-skeleton-basic.local
ServerAdmin webmaster@localhost
DocumentRoot /var/www/web-core-skeleton-basic/httpdocs
<Directory "/var/www/web-core-skeleton-basic/httpdocs">
        AllowOverride All
</Directory>
```
 
