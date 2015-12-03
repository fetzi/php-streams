<?php
/**
 * Class for streaming the content of an array
 *
 * @package Jopic\Streams
 * @author  Johannes Pichler <johannes.pichler@jopic.at>
 */

namespace Jopic\Streams;


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
        foreach($this->list as $key => $value) {
            if(!$this->isFilterDefined() || $this->filterFnc->__invoke($key, $value)) {
                $function($key, $value);

            }

            if($i == $this->limit) {
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
}