# 1. Installation
* Download the [latest release](https://github.com/pressmind/web-core-skeleton-basic/releases/latest).
* Extract the sources to a directory on your webserver (for example /var/www/vhosts).
* Create a MySQL database.

```shell script
mysql -u root -p;
mysql> CREATE DATABASE my_new_database;
mysql> CREATE USER 'my_database_user'@'localhost' IDENTIFIED BY 'my_database_password';
mysql> GRANT ALL ON my_new_database.* TO 'my_database_user'@'localhost' WITH GRANT OPTION;
``` 

* On a console, go to the base folder.

```shell script
cd /var/www/vhosts/web-core-skeleton-basic
```

* Install composer dependencies.

```shell script
composer install
```

* Go to the application/cli directory and run the install.php script.

```shell script
cd web-core-skeleton-basic/application/cli
php install.php
```

* You will be asked for some information on database and pressmind credentials.

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

* This will install the necessary database tables and generate the needed model definitions for the media object types.  
Additionally, some basic example php files that show the use of views are generated in the folder /application/Custom/Views as well as some html files with information on the installed media object types. You can find these under: httpdocs/docs/objecttypes.
* Once the install script is done, configure your webserver to serve files from the httpdocs folder.

```apacheconfig
#Example Apache2 Configuration
<VirtualHost *:80>
    ServerName web-core-skeleton-basic.local
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/vhosts/web-core-skeleton-basic/httpdocs
    <Directory "/var/www/vhosts/web-core-skeleton-basic/httpdocs">
        Order allow, deny
        Allow from all
        AllowOverride All
    </Directory>
</VirtualHost>
```

* For security reasons, all application and library files live outside the webservers document_root.
* Please make sure that no php open_basedir restrictions prevent php from accessing the folders directly above httpdocs.

## 2. Import from pressmind®
To import data from pressmind®, run the script application/cli/import.php.

In order to test the import functionality, it is a good idea to import a single media object from pressmind®.
```shell script
# Import single media object (123456 represents a valid media object id in your pressmind® userspace)
php import.php mediaobject 123456
```

Point your browser to the detail.php example script of your virtual host with an id-parameter:   
http://web-core-skeleton-basic.local/detail.php?id=123456  
You now should see the rendered output of the currently imported media object.

Check the logs folder (application/logs) for error messages if something is not working as expected.

In order to do a fullimport (which is recommended after a fresh install), add the argument fullimport.
```shell script
# Full Import
php import.php fullimport
```
Depending on the amount of data that is stored in pressmind, the full import may take time.  
For each media object all descriptive and touristic data will be imported into the database. Additionally, all related files and images will be downloaded to the folder httpdocs/assets.


## 3. Configure crontab
Add the line to your crontab. The cron job runs the scheduler, see config.json for advanced configuration.
```
* * * * * php /your/install/path/application/cli/cron.php  > /dev/null 2>&1
```

## 4. Ready!

