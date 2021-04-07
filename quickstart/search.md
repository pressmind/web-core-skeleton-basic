# Search
The pressmind sdk has the following search features:

Search conditions
* [Search all](#search-all)
* [Search by media object type](#search-by-media-object-type)
* [Search by term in fulltext index](#search-by-term-in-fulltext-index)
* [Search by price range](#search-by-price-range)
* [Search by departure date in date range](#search-by-departure-date-in-date-range)
* [Search by duration](#search-by-duration)
* [Search by transport type](#search-by-transport-type)
* [Search by visibility](##search-by-media-object-visibility)
* [Search by state](#search-by-state)
* [Search by valid from, valid to](#search-by-valid-from-valid-to)
* [Search by pool](#search-by-pool)
* [Search by brand](#search-by-brand)
* [Search by category tree items](#search-by-category-tree-items)

Other functions
*  [Order by](#order-by)
*  [Pagination](#pagination)
*  [List attributes / category tree items by search (for building filters)](#building-filters-bases-on-the-search-request)
*  Caching see [Configuration Documentation](config.md)

It's possible to combine all search conditions, 
the examples below are showing some use cases.

## Example search queries:

### Search all

```php
<?php
include_once 'bootstrap.php';

$search = new Pressmind\Search();

$results = $search->getResults(true);

foreach ($results as $mediaObject) {
    echo "<br>";
    echo $mediaObject->getId()."<br>";
    echo $mediaObject->name."<br>";
    echo number_format($mediaObject->getCheapestPrice()->price_total, 0, ',', '.')."<br>";
    echo $mediaObject->getPrettyUrl()."<br>";
}
```

### Search by media object type
This example lists 100 media objects orderd by price ascending by a defined object type.
```php
<?php
include_once 'bootstrap.php';

$search = new Pressmind\Search(
    [
            \Pressmind\Search\Condition\ObjectType::create(607)
    ],
    [
        'start' => 0,
        'length' => 100
    ]
);

$results = $search->getResults(true);

foreach ($results as $mediaObject) {
    echo "<br>";
    echo $mediaObject->getId()."<br>";
    echo $mediaObject->name."<br>";
    echo number_format($mediaObject->getCheapestPrice()->price_total, 0, ',', '.')."<br>";
    echo $mediaObject->getPrettyUrl()."<br>";
}
```

### Search by term in fulltext index
This example searches by the term "Italien" in the defined media object type. 
the object type is required.

```php
<?php
include_once 'bootstrap.php';

$search = new Pressmind\Search(
    [
        \Pressmind\Search\Condition\Fulltext::create('Italien', ['fulltext'], 'AND', 'NATURAL LANGUAGE MODE'),
        \Pressmind\Search\Condition\ObjectType::create(607) // required for fulltext search 
    ]
);

$results = $search->getResults(true);

foreach ($results as $mediaObject) {
    echo "<br>";
    echo $mediaObject->getId()."<br>";
    echo $mediaObject->name."<br>";
    echo number_format($mediaObject->getCheapestPrice()->price_total, 0, ',', '.')."<br>";
    echo $mediaObject->getPrettyUrl()."<br>";
}
```
### Search by price range
This example lists all media objects with a valid price between 1 and 125 EUR.
```php
<?php
include_once 'bootstrap.php';

$search = new Pressmind\Search(
    [
              \Pressmind\Search\Condition\PriceRange::create(1, 125),
    ]
);

$results = $search->getResults(true);

foreach ($results as $mediaObject) {
    echo "<br>";
    echo $mediaObject->getId()."<br>";
    echo $mediaObject->name."<br>";
    echo number_format($mediaObject->getCheapestPrice()->price_total, 0, ',', '.')."<br>";
    echo $mediaObject->getPrettyUrl()."<br>";
}
```


### Search by departure date in date range
This example lists all media objects in the defined daterange.
It delivers touristic media objects that have the departure date in the defined range.

Correspondes to the sql statement 'select * from dates where 
date_departure BETWEEN :date_from AND :date_to';

```php
<?php
include_once 'bootstrap.php';

$search = new Pressmind\Search(
    [
         \Pressmind\Search\Condition\DateRange::create(new DateTime('2020-01-01'), new DateTime('2022-12-31')),
    ]
);

$results = $search->getResults(true);

foreach ($results as $mediaObject) {
    echo "<br>";
    echo $mediaObject->getId()."<br>";
    echo $mediaObject->name."<br>";
    echo number_format($mediaObject->getCheapestPrice()->price_total, 0, ',', '.')."<br>";
    echo $mediaObject->getPrettyUrl()."<br>";
}
```

### Search by Duration
This example lists all media object that have a touristic travel duration in the defined range.
```php
<?php
include_once 'bootstrap.php';

$search = new Pressmind\Search(
    [
         \Pressmind\Search\Condition\DurationRange::create(5, 8),
    ]
);

$results = $search->getResults(true);

foreach ($results as $mediaObject) {
    echo "<br>";
    echo $mediaObject->getId()."<br>";
    echo $mediaObject->name."<br>";
    echo number_format($mediaObject->getCheapestPrice()->price_total, 0, ',', '.')."<br>";
    echo $mediaObject->getPrettyUrl()."<br>";
}
```

### Search by transport type
This example lists all media objects with the defined transport type.
The transport type is customer defined value

```php
<?php
include_once 'bootstrap.php';

$search = new Pressmind\Search(
    [
        \Pressmind\Search\Condition\Transport::create('PKW')
    ]
);

$results = $search->getResults(true);

foreach ($results as $mediaObject) {
    echo "<br>";
    echo $mediaObject->getId()."<br>";
    echo $mediaObject->name."<br>";
    echo number_format($mediaObject->getCheapestPrice()->price_total, 0, ',', '.')."<br>";
    echo $mediaObject->getPrettyUrl()."<br>";
}
```


### Search by Visibility
This example lists all media object that have the defined visibilities.
if no condition is set visibility 30 is set. 
allowed visibilities must also defined in config.json.

**Visibility map**:

| name | value
|---	|:---:
| Nobody  	|10  
| Public  	|30 (default)  
| Extranet  |40
| Intranet 	|50
| Hidden 	|60

```php
<?php
include_once 'bootstrap.php';

$search = new Pressmind\Search(
    [
        \Pressmind\Search\Condition\Visibility::create([30,10])
    ]
);

$results = $search->getResults(true);

foreach ($results as $mediaObject) {
    echo "<br>";
    echo $mediaObject->getId()."<br>";
    echo $mediaObject->name."<br>";
    echo number_format($mediaObject->getCheapestPrice()->price_total, 0, ',', '.')."<br>";
    echo $mediaObject->getPrettyUrl()."<br>";
}
```

### Search by state
This example lists all media object that have the defined states.

**State map**:

| name                              | value
|---	                            |:---:
|Draft                              |30
|Pending Review                     |40
|OK|50                              |
|Closed|60                          |
|Closed (reason: age)|70            |
|Closed (reason: law)|80            |
|Closed (reason: bad quality)       |90 
|Closed (reason: duplicate content) |100
|Closed (reason: technical error)   |110
|Imported                           |200

```php
<?php
include_once 'bootstrap.php';

$search = new Pressmind\Search(
    [
        \Pressmind\Search\Condition\State::create([100])
     ]
);

$results = $search->getResults(true);

foreach ($results as $mediaObject) {
    echo "<br>";
    echo $mediaObject->getId()."<br>";
    echo $mediaObject->name."<br>";
    echo number_format($mediaObject->getCheapestPrice()->price_total, 0, ',', '.')."<br>";
    echo $mediaObject->getPrettyUrl()."<br>";
}
```

### Search by valid from, valid to
This example lists all media objects that have the defined "valid from", "valid to" range

```php
<?php
include_once 'bootstrap.php';

$search = new Pressmind\Search(
    [
        \Pressmind\Search\Condition\Validity::create(new DateTime('2020-01-01'),new DateTime('2022-01-01'))
    ]
);

$results = $search->getResults(true);

foreach ($results as $mediaObject) {
    echo "<br>";
    echo $mediaObject->getId()."<br>";
    echo $mediaObject->name."<br>";
    echo number_format($mediaObject->getCheapestPrice()->price_total, 0, ',', '.')."<br>";
    echo $mediaObject->getPrettyUrl()."<br>";
}
```

### Search by pool
this example lists all media object that have the defined pools.

```php
<?php
include_once 'bootstrap.php';

$search = new Pressmind\Search(
    [
        \Pressmind\Search\Condition\Pool::create([1393])
    ]
);

$results = $search->getResults(true);

foreach ($results as $mediaObject) {
    echo "<br>";
    echo $mediaObject->getId()."<br>";
    echo $mediaObject->name."<br>";
    echo number_format($mediaObject->getCheapestPrice()->price_total, 0, ',', '.')."<br>";
    echo $mediaObject->getPrettyUrl()."<br>";
}
```


### Search by brand
This example lists media objects with the defined brands.

```php
<?php
include_once 'bootstrap.php';

$search = new Pressmind\Search(
    [
        \Pressmind\Search\Condition\Brand::create([123])
    ]
);

$results = $search->getResults(true);

foreach ($results as $mediaObject) {
    echo "<br>";
    echo $mediaObject->getId()."<br>";
    echo $mediaObject->name."<br>";
    echo number_format($mediaObject->getCheapestPrice()->price_total, 0, ',', '.')."<br>";
    echo $mediaObject->getPrettyUrl()."<br>";
}
```

### Search by category tree items
This example lists all media objects that have the defined 
category tree items joined to the defined property.

The category tree item ids are uuid's.
If you need these id's there are 3 ways:
1. right click on a tree item in the pressmind application and select 'copy id'
2. list all available id's with a search filter, documentation found [here](search_filter.md).
3. lookup in the database table: pmt2core_category_tree_items

```php
<?php
include_once 'bootstrap.php';

$search = new Pressmind\Search(
    [
        \Pressmind\Search\Condition\Category::create('zielgebiet_default', ['304E15ED-302F-CD33-9153-14B8C6F955BD', '4C5833CB-F29A-A0F4-5A10-14B762FB4019', '78321653-CF81-2EF1-ED02-9D07E01651C1']),
    ]
);

$results = $search->getResults(true);

foreach ($results as $mediaObject) {
    echo "<br>";
    echo $mediaObject->getId()."<br>";
    echo $mediaObject->name."<br>";
    echo number_format($mediaObject->getCheapestPrice()->price_total, 0, ',', '.')."<br>";
    echo $mediaObject->getPrettyUrl()."<br>";
}
```



### Order by
This examples has a attached order by clausel.
Following values are allowed at this moment: price, code, name, RAND()

```php
<?php
include_once 'bootstrap.php';

$search = new Pressmind\Search(
    [
        \Pressmind\Search\Condition\ObjectType::create(607),
        \Pressmind\Search\Condition\PriceRange::create(1, 99999)
    ],
    [
        'start' => 0,
        'length' => 100
    ],
    [
        'price' => 'asc' // order by price requires the price condition
        //'code' => 'asc'
        //'name' => 'asc'
        //'' => 'RAND()'
    ]
);

$results = $search->getResults(true);

foreach ($results as $mediaObject) {
    echo "<br>";
    echo $mediaObject->getId()."<br>";
    echo $mediaObject->name."<br>";
    echo number_format($mediaObject->getCheapestPrice()->price_total, 0, ',', '.')."<br>";
    echo $mediaObject->getPrettyUrl()."<br>";
}
```


### Pagination
This example brings you the basic function to build a pagination.

```php
<?php
include_once 'bootstrap.php';

$search = new Pressmind\Search(
    [
        \Pressmind\Search\Condition\ObjectType::create(607),
    ],
    [ 
        'start' => 0,
        'length' => 100
    ]
);

// activate the paginator, increment current page value per page in your application
$search->setPaginator(Pressmind\Search\Paginator::create(12 /*page size*/, 0 /* current page */));

$results = $search->getResults(true);

// this function must be called after the getResults statement.
echo 'total results: '.$search->getTotalResultCount()."<br>";
echo 'total pages: '.$search->getPaginator()->getTotalPages()."<br>";
echo 'current page: '.$search->getPaginator()->getCurrentPage()."<br>";

foreach ($results as $mediaObject) {
    echo "<br>";
    echo $mediaObject->getId()."<br>";
    echo $mediaObject->name."<br>";
    echo number_format($mediaObject->getCheapestPrice()->price_total, 0, ',', '.')."<br>";
    echo $mediaObject->getPrettyUrl()."<br>";
}
```

###  Building filters bases on the search request
This example displays all media object in defined price range and delivers 
possible filter options based on the search conditions. 
so it's possible to build search filters

```php
<?php
include_once 'bootstrap.php';

$search = new Pressmind\Search(
    [
         \Pressmind\Search\Condition\PriceRange::create(1, 100000),
    ],
    [ 
        'start' => 0,
        'length' => 100
    ]
);

// activate the paginator, increment current page value per page in your application
$search->setPaginator(Pressmind\Search\Paginator::create(12 /*page size*/, 0 /* current page */));

$results = $search->getResults(true);

// this function must be called after the getResults statement.
echo 'total results: '.$search->getTotalResultCount()."<br>";
echo 'total pages: '.$search->getPaginator()->getTotalPages()."<br>";
echo 'current page: '.$search->getPaginator()->getCurrentPage()."<br>";

// display the search result
foreach ($results as $mediaObject) {
    echo "<br>";
    echo $mediaObject->getId()."<br>";
    echo $mediaObject->name."<br>";
    echo number_format($mediaObject->getCheapestPrice()->price_total, 0, ',', '.')."<br>";
    echo $mediaObject->getPrettyUrl()."<br>";
}

// display the search filter
$category_filter = new \Pressmind\Search\Filter\Category('1207', $search);
foreach ($category_filter->getResult() as $id => $tree_item) {
    echo  '<pre>' . $id . ': ' . $tree_item->name . '</pre>';
}

$price_range_filter = new \Pressmind\Search\Filter\PriceRange($search);
echo "min price: ".$price_range_filter->getResult()->min."<br>";
echo "max price: ".$price_range_filter->getResult()->max."<br>";

$duration_filter = new \Pressmind\Search\Filter\Duration($search);
echo "min duration: ".$duration_filter->getResult()->min."<br>";
echo "max duration: ".$duration_filter->getResult()->max."<br>";

$departure_date_filter = new \Pressmind\Search\Filter\DepartureDate($search);
echo "min departure: ".$departure_date_filter->getResult()->from->format('d.m.Y')."<br>";
echo "max departure: ".$departure_date_filter->getResult()->to->format('d.m.Y')."<br>";

```