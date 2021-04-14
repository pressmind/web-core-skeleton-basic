# Pretty URLs

The pressmind SDK contains a simple routing and url handling. 

## Concept
Each media object has its own url to create unique urls or content silos.
Each media object route has a default prefix which is built by the media object type name.
   
**Basic example:**

default url pattern:

```
your-domain.com/hotel/hotel-name/
```

structure:
```
your-domain.com[PREFIX][FIELD][SUFFIX]
```

### More URL examples
You can configure the url structure according to the following examples:

Example 1:
```
your-domain.com/hotel/hotel-name.html
```
Example 2:
```
your-domain.com/hotel/hotel-name/
```
Example 3:
```
your-domain.com/hotel-name/
```

## Strategy
Each url must be unique, but you can configure the url strategy for your purpose.

The pressmind sdk has the following url strategies:

* none
* unique
* count-up

### Strategy 'none'
This strategy means that a route can **match to several media objects**.
For example you can call your-domain.com/trips/spain-for-bikers/,
the system delivers one or more media objects which are displayed in the frontend.
This case is useful for seo optimized travel products. Thus, it may happen that the tour operator sells the product year by year, 
but the product description has changes per season. 
Therefore, it is possible to show one or more product variants under one url.

### Strategy 'unique'
This strategy means that a route can **match only one media object**. 
If there is a media object which has the same route, the web-core throws an error during import.

### Strategy 'count-up'
This strategy means that a route can **match only one media object**. If there is a media object which has the
same route, the pressmind sdk adds an incremented integer to create a unique url.

For example:

**your-domain.com/trips/spain-for-bikers/** is attached to media object 12345

**your-domain.com/trips/spain-for-bikers-1/** is attached to media object 12346

Structure:

**your-domain.com/trips/spain-for-bikers-[COUNTER]/**


### How to configure
It is possible to configure this option per media object type. 
All configuration can be done in config.json.
See [Configuration Documentation](config.md) for detailed properties.

After the change of this values, you have to rebuild the routes:

```shell
php rebuild_routes.php
```



