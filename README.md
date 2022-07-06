# wpdb-engine-for-latitude

Use the [latitude](https://github.com/shadowhand/latitude) query builder to build queries for use in `wpdb`.

## Warning

This package is currently in development and is subject to breaking changes without notice until v1.0 has been tagged.

It is one in a series of [WordPress toys](https://github.com/ssnepenthe?tab=repositories&q=topic%3Atoy+topic%3Awordpress&type=&language=&sort=) I have been working on with the intention of exploring ways to modernize the feel of working with WordPress.

As the label suggests, it should be treated as a toy.


## Installation

```sh
composer require ssnepenthe/wpdb-engine-for-latitude
```

## Usage

Start by reviewing the [latitude documentation](https://latitude.shadowhand.com/). Using this package will be largely the same.

The important differences are noted below:

### WpdbEngine

The engine provided to the query factory should always be an instance of `WpdbEngine`:

```php
use Latitude\QueryBuilder\QueryFactory;
use WpdbEngineForLatitude\WpdbEngine;

$factory = new QueryFactory(new WpdbEngine());
```

### Field Function

In place of the `Latitude\QueryBuilder\field()` function, use `WpdbEngineForLatitude\field()`:

```php
use function WpdbEngineForLatitude\field;

$query = $factory
    ->select('id', 'username')
    ->from('users')
    ->where(field('id')->eq(5))
    ->compile();
```

### Search Function

In place of the `Latitude\QueryBuilder\search()` function, use `WpdbEngineForLatitude\search()`:

```php
use function WpdbEngineForLatitude\search;

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
