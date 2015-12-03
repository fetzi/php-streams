# PHP Stream Library
Library for streaming contents of PHP arrays.

## Installation
TBD

## Usage
The library distinguishes between Lists (array with numeric indices) and and other Arrays.
Therefore two initializations are possible

* `$stream = Stream::ofList(array(1, 2, 3));` for Lists
* `$arrayStream = Stream::ofArray(array("key1" => "value1""));` for array with different key types

## Examples
```php
Stream::ofList(array(1, 2, 3))
    ->skip(0)
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
