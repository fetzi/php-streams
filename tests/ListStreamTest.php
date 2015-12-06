<?php


/**
 * Class ListStreamTest
 * @author Johannes Pichler <johannes.pichler@jopic.at>
 */
class ListStreamTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Jopic\Streams\ListStream
     */
    private $stream;

    public function setUp()
    {
        $this->stream = Jopic\Stream::ofList(array(
            1, 2, 3, 4, 5, 6
        ));
    }

    public function testLimitOnListStream()
    {
        $this->stream->limit(3);
        $this->assertEquals(3, count($this->stream->toArray()));

        $this->stream->limit(1);
        $this->assertEquals(1, count($this->stream->toArray()));

        $this->stream->limit(0);
        $this->assertEquals(0, count($this->stream->toArray()));
    }

    public function testSkipElementsOnListStream()
    {
        $this->stream->skip(3);

        $result = $this->stream->toArray();
        $this->assertEquals(3, count($result));
        $this->assertEquals(4, $result[0]);

        $this->stream->skip(0);
        $result = $this->stream->toArray();
        $this->assertEquals(6, count($result));
        $this->assertEquals(1, $result[0]);
    }

    public function testStepFunctionChangeOnListStream()
    {
        $this->stream->step(function ($i) {
            return $i + 2;
        });

        $result = $this->stream->toArray();

        $this->assertEquals(3, count($result));
        $this->assertEquals(3, $result[1]);
    }

    public function testFilterFunctionOnListStream()
    {
        $this->stream->filter(function ($item) {
            return $item % 2 == 0;
        });

        $result = $this->stream->toArray();

        $this->assertEquals(3, count($result));
        $this->assertEquals(2, $result[0]);
    }

    public function testResetOnListStream()
    {
        $this->stream
            ->limit(1)
            ->skip(1)
            ->step(function ($i) {
                return $i + 2;
            })
            ->filter(function ($item) {
                return $item == 1;
            });

        $result = $this->stream->toArray();

        $this->assertEquals(0, count($result));

        $this->stream->reset();

        $this->assertEquals(6, count($this->stream->toArray()));
    }

    public function testEachOnListStream()
    {
        $result = array();
        $this->stream->each(function ($item) use (&$result) {
            $result[] = $item;
        });

        $this->assertEquals(6, count($result));
    }

    public function testCollectOnListStream()
    {
        $this->assertEquals('1, 2, 3, 4, 5, 6', $this->stream->collect(', '));
    }

    public function testMapToArrayOnListStream() {
        $this->stream->map(function($item) {
            return $item +1;
        });

        $this->assertEquals('2, 3, 4, 5, 6, 7', $this->stream->collect(', '));
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testSumOnListStreamWithoutMapFunction() {
        $this->stream = \Jopic\Stream::ofList(array("a", "b"));
        $this->stream->sum();
    }

    public function testMapSumOnListStream()
    {
        $this->stream = \Jopic\Stream::ofList(array(
            array(
                'color' => 'red',
                'price' => 20
            ),
            array(
                'color' => 'blue',
                'price' => 100
            ),
            array(
                'color' => 'red',
                'price' => 10
            )
        ));

        $this->stream
            ->filter(function ($item) {
                return strcmp($item['color'], 'red') == 0;
            })
            ->map(function ($item) {
                return $item["price"];
            });

        $this->assertEquals(30, $this->stream->sum());
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testMinOnListStreamWithoutMapFunction() {
        $this->stream = \Jopic\Stream::ofList(array("a", "b"));
        $this->stream->min();
    }

    public function testMinOnListStream() {
        $this->assertEquals(1, $this->stream->min());
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testMaxOnListStreamWithoutMapFunction() {
        $this->stream = \Jopic\Stream::ofList(array("a", "b"));
        $this->stream->max();
    }

    public function testMaxOnListStream() {
        $this->assertEquals(6, $this->stream->max());
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testAvgOnListStreamWithoutMapFunction() {
        $this->stream = \Jopic\Stream::ofList(array("a", "b"));
        $this->stream->avg();
    }

    public function testAvgOnListStream() {
        $this->assertEquals(3.5, $this->stream->avg());
    }
}