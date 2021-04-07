# Search filter
Based on search conditions it's possible to 
get possible attributes to filter the search result.

See [Search Documentation](search.md) to build search conditions

## Price range
Delivers the min and the max price based on the search result
```php
    include_once 'bootstrap.php';
    $search = new Pressmind\Search(
        [
            Pressmind\Search\Condition\ObjectType::create(123),
        ]
    );

    // get the min and max price, based on the current search
    $pRangeFilter = new Pressmind\Search\Filter\PriceRange($search);
    $pRange = $pRangeFilter->getResult();
    print_r($pRange);
```

## Duration range
Delivers the min and the max duration based on the search result
```php
    include_once 'bootstrap.php';
    $search = new Pressmind\Search(
        [
            Pressmind\Search\Condition\ObjectType::create(123),
        ]
    );

    $dRangeFilter = new Pressmind\Search\Filter\Duration($search);
    $dRange = $dRangeFilter->getResult();
    print_r($dRange);
```

## Categorytree Items 
Delivers the categorytree items like (destination, type, etc) based on the search result
```php
    include_once 'bootstrap.php';
    
    $id_tree = 1001;
    $search = new Pressmind\Search(
        [
            Pressmind\Search\Condition\ObjectType::create(123),
        ]
    );

    $tree = new Pressmind\Search\Filter\Category($id_tree, $search);
    $treeItems = $tree->getResult();
    print_r($treeItems);
```