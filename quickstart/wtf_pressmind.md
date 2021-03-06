# Understanding pressmind® PIM

pressmind® PIM is an object based product management system for tourism. The main application is developed as standard
software.

Each of our customers has a use case driven datamodel. This means, that the stored objects are tailormade for the
specific customer. These objects are named "media objects".

Before you can build your application based on pressmind, you have to understand the specific customer datamodel.

### What is a pressmind® media object?

A media object is a complex structured description for an entity. It can contain multiple datatypes, mostly with a media
focus. Each object can have joins to other or its own object types.

The following example shows a simple object model for a "hotel only" tour operator.

**Example object:**

### Hotel

|Key            |Type            |Customer specific |Description    |
|---	        |---	        |:---:             |---	                                                                            |
|id            |int            |                  |a unique id            |
|code        |string        |                  |the foreign key for this hotel                |
|name        |Plaintext[]    |x                 |a list of sections (mostly one section per language or text variants)                |
|star-category  |CategoryTree   |x                 |hotel category pictured as CategoryTree* Reference                                                                            |
|description    |Text[]        |x                 |a list of sections (mostly one section per language or text variants)                |
|destination    |CategoryTree[] |x                 |destination with n subdestinations as CategoryTree Reference (multidimensional tree)            |
|images        |Image[]        |x                 |a list of images and metadata            |
|room types    |ObjectLink[]   |x                 |a reference list of "room type" objects|
|geolocation    |geolocation[]  |x                 |list of geocoordinates|
|touristic    |Touristic      |case specific     | |
|...            |...            |x                 |in reality we have about 100 keys in a media object   | 

### Room Type

(These objects are linked from hotel objects.)

|Key            |Type            |Customer specific|Description    |
|---	        |---	        |:---:            |---	                                                                            |
|id            |int            |                 |unique id
|code        |string        |                 |the foreign key id for this hotel                |
|name        |Plaintext[]    |x                |a list of sections (mostly one section per language or text variants)                |
|description    |Text[]        |x                |a list of sections (mostly one section per language or text variants)                |
|images        |Image[]        |x                |a list of images and metadata            |

Please note: These are simply examples to give you a first overview. They do not contain the complete detail level.

## What about travel dates, prices, booking and the other tourism stuff?

There are a many different tour operator types on the market. It seems that they are all selling the same holidays, but each
tour operator

* ... has an individual production (and system) enviroment.
* ... has a complex product pricing, based on saisons, date restrictions and complex discounts.
* ... has different product histories.
* ... has destination specific suppliers and products.
* ... has special markets, audiences and target groups.

Thus, our integration team has developed more than one integration concept for touristic data

|Name            |Touroperator Type      |recommmend Integration Endpoint    |
|---	        |---                    |--- |
|Case 1            |group travel B2C, transport/arrival type: bus, plane. (max. 50 travel offers per product)|pressmind® my.content|
|Case 2            |group travel B2B, transport/arrival type: bus, plane, hotel only|pressmind® my.content|
|Case 3            |FIT B2C, transport/arrival type: bus, plane, hotel only, daily arrival above 100 per product| sdk/web-core|

### What is travel offer?

A travel offer is plausible, calculated and bookable travel offer.

How to calculate per product:
(Possible Arrival Days) * (Possible Stays) * (Possible Roomtypes) * (other Options like Boardtype or Extras) = (Possible
Offers)

|Possible Arrival Days  |Possible Stays |Possible Roomtypes |other Options|Possible Offers    |
|:---:	                |---	        |:---:              |:---: |:---:	                                                                            |
|365                    |3, 5, 7 days   |3                  | 4 |17520                                                                                |

If you plan to sell 2000 hotels with 17500 travel offers, your database contains 35 million records. Be aware,
that you need some computing power to serve this data in your application.

## What is the main concept of the pressmind® web-core?

The pressmind® web-core is a headless content cache for bookable and none bookable content.

Both content types can be mapped to the web-core:

|Content type  |Data origin |
|---	       |---	            
|none-bookable |pressmind® PIM
|bookable      |tour operator system


## Data (field) types
These datatypes can be used to describe a media object like a product or any other entity.

|type  |description |
|---	       |---	            
|plaintext| plaintext without tags and linebreaks
|text| html text but only with these tags p, i, u, b, strong, p, br, ul, li
|categorytree| tree structured attribute like "planet > continent > country > district > city"
|table| html table
|picture| a picture list, each picture can have different sizes like thumbnail, teaser, etc.
|location| one or more geo coordinate with names
|objectlink| one or more linked media objects
|date| datefield Y-m-d H:i:s
|key_value| simple key value map
|file|one or more file attachments with description
|touristic|complex object with touristic offer decriptions (dates, prices, saisons, discounts)


# Examples


## List products

You can use this query for building teasers, landingpages and other product related stuff.

```php
<?php
require_once dirname(__DIR__) . '/bootstrap.php';
use Pressmind\Search;

$search = new Search(
    [
        
        /**
        Search in Object Type ID 169 (in this case 169 means roundtrips)
        */
        Search\Condition\ObjectType::create(169),
        
        /**
         Search Products between 1-5000 EUR
        */
        Search\Condition\PriceRange::create(1, 5000),
        
        /**
            Search in spefic destination 
        */
        Search\Condition\Category::create('destination_default', ['B9063101-0F6A-2322-83A6-FAF7A0D82827']),
        
        /**
         Search for travels in a specific date range
        */
        Search\Condition\DateRange::create(new DateTime('2020-06-01'), new DateTime('2020-07-31')),
        
        /**
         Search by term 
        */
        Search\Condition\Fulltext::create('Pisa', ['fulltext'], 'AND', 'NATURAL LANGUAGE MODE'), 
        
        /**
         Search only object with visibility is public
            10 = "Nobody"
            30 = "Public"
            40 = "Extranet"
            50 = "Intranet"
            70 = "Hidden"
        */
        Search\Condition\Visibility::create([30])
    ],
    [
        'start' => 0,
        'length' => 100
    ],
    [
        '' => 'RAND()'
    ]
);


$mediaObjects = $search->getResults();
foreach ($mediaObjects as $mediaObject) {
    /**
     Render the media object with the view "Roundtrip_Teaser.php"
     Viewfile naming convention: [OBJECTYPE_NAME]_[VIEWNAME].php
    */
    echo $mediaObject->render('Teaser'); 
}
```

## Simple view

See product list example above. The render function calls this file.
An existing example can be found here:
https://github.com/pressmind/wp-travelshop-theme/blob/master/travelshop/template-parts/pm-views/scaffolder/Detail1.txt

```php
<?php

use Pressmind\HelperFunctions;
use Pressmind\Search\CheapestPrice;

/**
 * $data is preloaded by render-function
 * @var array $data
 */

/**
 * Map the data to the custom object for better code complementation
 * @var Custom\MediaType\Roundtrip $moc
 */
$moc = $data['data'];

/**
 * The booking package contains all touristic specific data, like prices, travel dates, etc
 * @var Pressmind\ORM\Object\Touristic\Booking\Package[] $booking_packages
 */
$booking_packages = $data['booking_packages'];

/**
 * the basic media object contains generic metadata for the custom object
 * @var Pressmind\ORM\Object\MediaObject $mo
 */
$mo = $data['media_object'];


// Example Output object content:

echo '<p>Media Object ID: '.$mo->id.'</p>';
echo '<p>Media Object Name: '.$mo->name.'</p>';

echo number_format($mo->getCheapestPrice()->price_total, 2, ',', '.');
/**
  * Display some text information, not every property has content, so it's recommend to check this with "if empty" for better layouts..  
  * this is typical and recommend output proceed for pressmind fieldtypes: plaintext, text, code 
 */
echo !empty($moc->headline_default) ? '<h1>'.$moc->headline_default.'</h1>' : '';
echo !empty($moc->subline_default) ? '<h2>'.$moc->subline_default.'</h2>' : '';
echo !empty($moc->intro_default) ? '<p class="intro-text">'.$moc->intro_default.'</p>' : '';

/**
 * Display images,
 */
foreach ($moc->bilder_default as $picture) {?>

     <img src="<?php echo $picture->getUri('detail'); /* 'detail' is a defined image derivate, see config */ ?>" 
          title="<?php echo $picture->caption?>" 
          data-copyright="<?php echo $picture->copyright;?>" >
<?php } 

/**
Display object links
*/
foreach($moc->textbaustein_default as $textbaustein_link){

    $textbaustein_mo = new \Pressmind\ORM\Object\MediaObject($textbaustein_link->id_media_object_link, true);

    // if the linked object is not available (in most cases it must be public)
    if(empty($textbaustein_mo->id)){
        continue;
    }

    /**
     * this is for better code complementation in lovely ide's like phpstorm
     * @var $textbaustein_moc \Custom\MediaType\Textbaustein
     */
    $textbaustein_moc = $textbaustein_mo->getDataForLanguage();
    ?>
         <p><?php echo $textbaustein_moc->text_default; ?></p>
<?php } ?>
```
