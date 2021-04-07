# Pretty URLs

The pressmind SDK contains a simple routing and url handling. 

## Concept
Each media object has it's own url. to create unique url's or silos, 
each media object route has a default prefix build by the media object type name.
   
**Basic Example:**

default url pattern:

```
your-domain.com/hotel/hotel-name/
```

structure:
```
your-domain.com[PREFIX][FIELD][SUFFIX]
```


### More URL Examples

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
each url must be unique. 
but you can configure the url strategy for your purpose.

the pressmind sdk brings the following url strategies:

* none
* unique
* count-up

### Strategy 'none'
Means that a route can **match to several media objects**.
For example you can call your-domain.com/trips/spain-for-bikers/ 
the system delivers one ore more media objects for display in the frontend.
This case is useful for seo optimized travel products if you're 
selling a the product year by year, but the product description has changes per season.
So it's possible to show one or more product variants under one url.

### Strategy 'unique'
Means that a route can **match only one media object**. 
If the're is a media object which has the same route, the web-core throws an error during import.

### Strategy 'count-up'
Means that a route can **match only one media object**, but if the're is a media object which has the
same route, the pressmind sdk adds a incremented integer to create a unique url.

for example:
your-domain.com/trips/spain-for-bikers/ is attached to media object 12345
your-domain.com/trips/spain-for-bikers-1/ is attached to media object 12346

structure:
your-domain.com/trips/spain-for-bikers-[COUNTER]/


### How to configure
It's possible to configure this option per media object type. 
All configuration can be done in config.json.
See [Configuration Documentation](config.md) for detailed properties.

After change this values you have to rebuild the routes:

```shell
php rebuild_routes.php
```



