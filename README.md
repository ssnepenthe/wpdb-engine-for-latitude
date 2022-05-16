# wp-latitude-bridge

Use the [latitude](https://github.com/shadowhand/latitude) query builder to build queries for use in `wpdb`.

## Installation

NOT READY YET...

## Usage

Start by reviewing the [latitude documentation](https://latitude.shadowhand.com/). Using this package will be largely the same.

The important differences are noted below:

### WpdbEngine

The engine provided to the query factory should always be an instance of `WpdbEngine`:

```php
use Latitude\QueryBuilder\QueryFactory;
use WpLatitudeBridge\WpdbEngine;

$factory = new QueryFactory(new WpdbEngine());
```

### Field Function

In place of the `Latitude\QueryBuilder\field()` function, use `WpLatitudeBridge\field()`:

```php
use function WpLatitudeBridge\field;

$query = $factory
    ->select('id', 'username')
    ->from('users')
    ->where(field('id')->eq(5))
    ->compile();
```

### Search Function

In place of the `Latitude\QueryBuilder\search()` function, use `WpLatitudeBridge\search()`:

```php
use function WpLatitudeBridge\search;

$query = $factory
    ->select()
    ->from('users')
    ->where(search('first_name')->begins('john'))
    ->compile();
```

## With Wpdb

Once you have compiled a query instance you should pass the sql and params through the `wpdb->prepare()` method to get your final query string:

```php
global $wpdb;

$queryString = $wpdb->prepare($query->sql(), ...$query->params());
```

And finally use it with any of the `wpdb` query methods:

```php
$result = $wpdb->get_row($queryString);
```

## More Examples

Please refer to the tests in `tests/CodeReferenceExamplesTest.php` - The examples from the [`wpdb` code reference page](https://developer.wordpress.org/reference/classes/wpdb/) have been re-implemented there.
