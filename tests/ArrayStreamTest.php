<?php
include "bootstrap.php";

/**
 * Class ArrayStreamTest
 * @author Johannes Pichler <johannes.pichler@jopic.at>
 */
class ArrayStreamTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Jopic\Streams\ArrayStream
     */
    private $stream;

    public function setUp()
    {
        $this->stream = \Jopic\Stream::ofArray(array(
            "key1" => "value1",
            "key2" => "value2",
            "key3" => 3,
            4 => "value 4"
        ));
    }

    public function testFilterOnArrayStream()
    {
        $this->stream->filter(function ($key, $value) {
            return is_numeric($key);
        });

        $this->assertEquals(1, count($this->stream->toArray()));
    }

    public function testSkipOnArrayStream()
    {
        $this->stream->skip(2);

        $this->assertEquals(2, count($this->stream->toArray()));
    }

    public function testLimitOnArrayStream()
    {
        $this->stream->limit(3);

        $this->assertEquals(3, count($this->stream->toArray()));
    }

    public function testSkipLimitOnArrayStream()
    {
        $this->stream
            ->skip(1)
            ->limit(2);

        $result = $this->stream->toArray();
        $this->assertEquals(2, count($result));
        $this->assertEquals("value2", reset($result));
    }

    public function testMapToArrayOnArrayStream()
    {
        $this->stream->map(function ($key, $value) {
            return strlen($value);
        });

        $this->assertEquals(array('key1' => 6, 'key2' => 6, 'key3' => 1, 4 => 7), $this->stream->toArray());
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testSumOnArrayStreamWithoutMapFunction() {
        $this->stream->sum();
    }

    public function testMapSumOnArrayStream() {
        $this->stream->map(function ($key, $value) {
            return strlen($value);
        });

        $this->assertEquals(20, $this->stream->sum());
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testMinOnArrayStreamWithoutMapFunction() {
        $this->stream->min();
    }

    public function testMapMinOnArrayStream() {
        $this->stream->map(function ($key, $value) {
            return strlen($value);
        });

        $this->assertEquals(1, $this->stream->min());
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testMaxOnArrayStreamWithoutMapFunction() {
        $this->stream->min();
    }

    public function testMapMaxOnArrayStream() {
        $this->stream->map(function ($key, $value) {
            return strlen($value);
        });

        $this->assertEquals(7, $this->stream->max());
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testAvgOnArrayStreamWithoutMapFunction() {
        $this->stream->avg();
    }

    public function testMapAvgOnArrayStream() {
        $this->stream->map(function ($key, $value) {
            return strlen($value);
        });

        $this->assertEquals(5, $this->stream->avg());
    }
}