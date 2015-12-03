# PHP Streams Library
[![Build Status](https://travis-ci.org/fetzi/php-streams.svg?branch=master)](https://travis-ci.org/fetzi/php-streams)

Library for streaming contents of PHP arrays.

## Requirements
* PHP version >= 5.3

## Installation
TBD

## Usage

### Initialization
The library distinguishes between Lists (array with numeric indices) and and other Arrays.
Therefore two initializations are possible

* `$stream = Stream::ofList(array(1, 2, 3));` for Lists
* `$arrayStream = Stream::ofArray(array("key1" => "value1""));` for array with different key types

### limit
the method allows you to define the maximum number of records used in the result functions (`each`, `toArray`, `collect`)

```php
Stream::ofList(array(1, 2, 3))
    ->limit(1) // limits the stream to the first element
```

### skip
the method allows you to define the number of matching elements to skip

```php
Stream::ofList(array(1, 2, 3))
    ->skip(1) // the resulting elements are (2, 3)
```

### step (ListStream only)
the method allows you to define the step width function for the for loop

```php
Stream::ofList(array(1, 2, 3, 4, 5))
    ->step(function($i) { return $i + 2; }); // will iterate over the following elements (1, 3, 5)
```

### filter
the method allows you to apply a filter method on the stream elements

```php
Stream::ofList(array(1, 2, 3, 4, 5))
    ->filter(function($item) { return $item % 2 == 0; }); // will filter the elements (2, 4)
```

### reset
the method resets all actions done with `limit`, `skip`, `step` and `filter`

```php
Stream::ofList(array(1, 2, 3, 4, 5))
    ->filter(function($item) { return $item % 2 == 0; }) // matching elements (2, 4)
    ->reset(); // matching elements (1, 2, 3, 4, 5)
```

### each
the method iterates over all matching elements and executes the given function on each of them

```php
Stream::ofList(array(1, 2, 3, 4, 5))
    ->each(function($item) { echo $item; }); // will print all items
```

**Important**: The ArrayStream implementation of `each` is called with two parameters `key` and `value`

### toArray
the method collects all matching elements into a php array

```php
Stream::ofList(array(1, 2, 3, 4, 5))
    ->skip(2)
    ->limit(2)
    ->toArray(); // will return array(3, 4)
```

### collect (ListStream only)
the method collects all matching elements into a string seperated by the give seperator

```php
Stream::ofList(array(1, 2, 3))
    ->collect(','); // will return "1,2,3"
```

## Examples

Example to skip the first element, filter for odd values, limit the result to 3 and collect the matching elements into a concatinated string
```php
Stream::ofList(array(1, 2, 3, 4, 5, 6))
    ->skip(1)
    ->filter(function($item) {
        return $item % 2 == 1;
    })
    ->limit(3)
    ->collect(', ') // will return "1, 3, 5"
```

Example for associative array (filters for key is numeric and returns the matching elements as array
```php
Stream::ofArray(array(
            "key1" => "value1",
            "key2" => 2,
            "key3" => 3,
            4 => "value 4"
        ))
        ->filter(function($key, $value) {
            return is_numeric($key);
        })
        ->toArray(); // will return array(4 => "value 4")
```
