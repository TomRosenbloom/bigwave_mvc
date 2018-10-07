# DIY MVC

This is a home-rolled basic MVC application.

## Application requirements

- periodically processes the 'Lets Ride' events from the British Cycling site and presents them on a web page in both list and map view.
- takes JSON data and stores a subset in a local database
- exposes a v simple API for local use
- local data can be refreshed at any time
- events are shown in a Google map view and a list view
  - pop-ups
  - clustering
  - use of Spiderfy to separate map markers at same location
  - selecting event in map automatically scrolls list [and vice versa]
- searching and filtering of events
  - events within range of postcode
- 

## MVC features:

- all requests are directed to the site root folder /public/ (a level above the physical web root)
- htaccess file passes css/js/image requests to relevant folders, re-directs all others to index.php which includes autoloader (Composer autoloader) and bootstraps the application
- core component classes in /core/
  - App - parses url and executes controller action
  - Config - returns config values from config.ini
  - Database - singleton database connection
  - BaseModel - abstract base class for models. Defines common model properties and methods e.g. getWhere() which takes an array of 'parameter tuples' - name, value, comparator - to construct a SELECT ... WHERE query
  - BaseController - abstract base class for controllers



## Implementation

Some examples of how MVC is used to achieve the application requirements:

### Events in range



## Discussion

This is a very basic kind of MVC, useful as an exercise and demonstration only.

The level of abstraction in data access is pretty low. It's tightly coupled to SQL in an Active Record kind of way.