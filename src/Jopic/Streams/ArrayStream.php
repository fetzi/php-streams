<?php
/**
 * Class for streaming the content of an array
 *
 * @package Jopic\Streams
 * @author  Johannes Pichler <johannes.pichler@jopic.at>
 */

namespace Jopic\Streams;
use Zend\Cache\Exception\BadMethodCallException;


/**
 * Class ArrayStream
 * @author Johannes Pichler <johannes.pichler@jopic.at>
 */
class ArrayStream extends AbstractStream
{
    /**
     * method for iterating over all matching elements and executing a userdefined method on them
     *
     * @param callable $function the user defined function to be executed on matching elements
     */
    public function each($function)
    {
        $i = 0;
        $elements = 0;
        foreach($this->list as $key => $value) {
            if(!$this->isFilterDefined() || $this->filterFunction->__invoke($key, $value)) {
                if($i < $this->skip) {
                    $i++;
                    continue;
                }

                if($this->isMapDefined()) {
                    $function($key, $this->mapFunction->__invoke($key, $value));
                }
                else {
                    $function($key, $value);
                }

                $i++;
                $elements++;
            }

            if($elements == $this->limit) {
                break;
            }
        }
    }

    /**
     * method for retreiving all matching stream items
     *
     * @return mixed array of all matching elements
     */
    public function toArray() {
        $array = array();

        $this->each(function($key, $value) use (&$array) {
            $array[$key] = $value;
        });

        return $array;
    }

    /**
     * NOT IMPLEMENTED
     *
     * @param $step callable
     * @return null
     * @throws BadMethodCallException in all cases because step is not defined for associative arrays
     */
    public function step($step)
    {
        throw new \BadMethodCallException('step method is not available for ArrayStreams!');
    }


    /**
     * NOT IMPLEMENTED
     *
     * @param $seperator string
     * @return nothing
     * @throws BadMethodCallException in all cases because collect is not defined for associative arrays
     */
    public function collect($seperator)
    {
        throw new \BadMethodCallException('collect method is not available for ArrayStreams!');
    }
}