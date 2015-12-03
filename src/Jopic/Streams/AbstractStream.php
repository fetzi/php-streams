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
     * AbstractStream constructor.
     *
     * @param $list array the php array
     */
    public function __construct($list) {
        $this->list = $list;
        $this->reset();
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
     * method for checking if a filter function was specified
     *
     * @return bool true if filter function is defined
     */
    protected function isFilterDefined() {
        return !empty($this->filterFunction);
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