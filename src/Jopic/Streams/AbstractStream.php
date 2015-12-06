<?php
/**
 * Abstract class for defining basic stream functionality
 *
 * @package Jopic\Streams
 * @author  Johannes Pichler <johannes.pichler@jopic.at>
 */

namespace Jopic\Streams;


/**
 * Class AbstractStream
 * @author Johannes Pichler <johannes.pichler@jopic.at>
 */
abstract class AbstractStream
{
    const TYPE_MIXED = 0;
    const TYPE_NUMERIC = 1;
    const TYPE_OTHER = 2;
    /**
     * @var array the actual php array
     */
    protected $list;

    /**
     * @var int number of elements to skip
     */
    protected $skip = 0;

    /**
     * @var int max number of elements for iteration
     */
    protected $limit;

    /**
     * @var callable the step function for the for loop
     */
    protected $stepFunction;

    /**
     * @var callable the filter function
     */
    protected $filterFunction;

    /**
     * @var callable the map function
     */
    protected $mapFunction;

    /**
     * @var int constant for defining the data type over the map
     */
    protected $valueDatatype = self::TYPE_OTHER;

    /**
     * AbstractStream constructor.
     *
     * @param $list array the php array
     */
    public function __construct($list) {
        $this->list = $list;
        $this->reset();

        $this->determineValueDatatype();
    }

    /**
     * method for setting the result minit
     *
     * @param $limit int the result value
     * @return $this
     */
    public function limit($limit) {
        if($limit < $this->limit) {
            $this->limit = $limit;
        }

        return $this;
    }

    /**
     * method for setting the number of elements to skip
     *
     * @param $skip int number of elements to skip
     * @return $this
     */
    public function skip($skip) {
        $this->skip = $skip;
        return $this;
    }

    /**
     * method for defining the step function used in the for loop
     *
     * @param $step callable the step function
     * @return $this
     */
    public function step($step) {
        $this->stepFunction = $step;
        return $this;
    }

    /**
     * method for defining the filter method for the stream
     *
     * @param $filter callable the filter method
     * @return $this
     */
    public function filter($filter) {
        $this->filterFunction = $filter;
        return $this;
    }

    /**
     * method for resetting the stream options to the original state
     */
    public function reset() {
        $this->limit = count($this->list);
        $this->stepFunction = function($i) {
            return $i + 1;
        };
        $this->filterFunction = null;
        $this->skip = 0;
    }

    /**
     * method for defining the map function used for the iteration and aggregation functions
     *
     * @param $function callable the map function object
     * @return $this
     */
    public function map($function) {
        $this->mapFunction = $function;
        return $this;
    }

    /**
     * method for calculating the sum of all elements. if value is numeric no map function is required
     *
     * @return int the sum of all elements
     */
    public function sum() {
        $sum = 0;

        if($this->valueDatatype != self::TYPE_NUMERIC && !$this->isMapDefined()) {
            throw new \BadMethodCallException('the map function needs to be defined for sum function');
        }

        $this->each(function($p1, $p2 = null) use(&$sum) {
            $sum += is_null($p2) ? $p1 : $p2;
        });

        return $sum;
    }

    /**
     * method for calculating the min value of all elements
     *
     * @return int the minimum value of all elements
     */
    public function min() {
        $min = PHP_INT_MAX;

        if($this->valueDatatype != self::TYPE_NUMERIC && !$this->isMapDefined()) {
            throw new \BadMethodCallException('the map function needs to be defined for min function');
        }

        $this->each(function($p1, $p2 = null) use(&$min) {
            $value = is_null($p2) ? $p1 : $p2;
            $min = $value < $min ? $value : $min;
        });

        return $min;
    }

    /**
     * method for calculating the max value of all elements
     *
     * @return int the maximum value of all elements
     */
    public function max() {
        $max = PHP_INT_MAX * -1;

        if($this->valueDatatype != self::TYPE_NUMERIC && !$this->isMapDefined()) {
            throw new \BadMethodCallException('the map function needs to be defined for max function');
        }

        $this->each(function($p1, $p2 = null) use(&$max) {
            $value = is_null($p2) ? $p1 : $p2;
            $max = $value > $max ? $value : $max;
        });

        return $max;
    }

    /**
     * method for calculating the average value over all elements
     *
     * @return float the average value of all elements
     */
    public function avg() {
        $itemList = array();

        if($this->valueDatatype != self::TYPE_NUMERIC && !$this->isMapDefined()) {
            throw new \BadMethodCallException('the map function needs to be defined for max function');
        }

        $this->each(function($p1, $p2 = null) use(&$itemList) {
            $value = is_null($p2) ? $p1 : $p2;
            $itemList[] = $value;
        });

        $avg = array_sum($itemList) / count($itemList);
        return $avg;
    }

    /**
     * method for checking if a filter function was specified
     *
     * @return bool true if filter function is defined
     */
    protected function isFilterDefined() {
        return !empty($this->filterFunction);
    }

    /**
     * method for checking if the map function was specified
     *
     * @return bool true if map function is defined
     */
    protected function isMapDefined() {
        return !empty($this->mapFunction);
    }

    /**
     * method for determining the data type over all values in array
     */
    protected function determineValueDatatype() {
        if(empty($this->list)) {
            return;
        }

        $dataType = gettype(reset($this->list));

        foreach($this->list as $key => $value) {
            if(strcmp($dataType, gettype($value)) != 0) {
                $this->valueDatatype = self::TYPE_MIXED;
                return;
            }
        }

        switch($dataType) {
            case "integer":
            case "double":
                $this->valueDatatype = self::TYPE_NUMERIC;
                break;
            default:
                $this->valueDatatype = self::TYPE_OTHER;
                break;
        }

    }

    /**
     * method for executing the defined function for all relevant stream items
     *
     * @param $function callable function to execute on the stream items
     */
    public abstract function each($function);

    /**
     * method for retreiving all matching stream items
     *
     * @return mixed array of all matching elements
     */
    public abstract function toArray();

    /**
     * method for collecting and joining all matching elements
     *
     * @param $seperator string the seperator used for streaming
     * @return string the resulting joined string
     */
    public abstract function collect($seperator);

}