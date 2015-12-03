# PHP Stream Library
Library for streaming contents of PHP arrays.

## Requirements
* PHP version >= 5.3

## Installation
TBD

## Usage
The library distinguishes between Lists (array with numeric indices) and and other Arrays.
Therefore two initializations are possible

* `$stream = Stream::ofList(array(1, 2, 3));` for Lists
* `$arrayStream = Stream::ofArray(array("key1" => "value1""));` for array with different key types

## Examples

Example to skip the first element, filter for odd values, limit the result to 3 and print the matching values
```php
Stream::ofList(array(1, 2, 3, 4, 5, 6))
    ->skip(1)
    ->filter(function($item) {
        return $item % 2 == 1;
    })
    ->limit(3)
    ->each(
        function($element) {
            echo $element;
        }
    );
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
        ->toArray();
```
