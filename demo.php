<?php
include "vendor/autoload.php";

use \Jopic\Stream;

$listStream = Stream::ofList(array(1, 2, 3));

$listStream->limit(3)
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

echo "<br><br>";

$result = $listStream->toArray();
var_dump($result);

echo "<br><br>";

$listStream->reset();
$result = $listStream->toArray();
var_dump($result);

echo "<br><br>";

$arrayStream = Stream::ofArray(array(
    "key1" => "value1",
    "key2" => "value2",
    "key3" => "value3"
));

$arrayStream
    ->filter(function($key, $value) {
        return strcmp($key, "key2") == 0;
    })
    ->each(function($key, $value) {
   echo "Key: " . $key . " - Value: " . $value . "<br>";
});

echo "<br><br>";

$result = $arrayStream->toArray();
var_dump($result);