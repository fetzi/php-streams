<?php
/**
 * Class for streaming the content of an array with numeric indices
 *
 * @package Jopic\Streams
 * @author  Johannes Pichler <johannes.pichler@jopic.at>
 */

namespace Jopic\Streams;

/**
 * Class ListStream
 * @author Johannes Pichler <johannes.pichler@jopic.at>
 */
class ListStream extends AbstractStream
{
    /**
     * method for iterating over all matching elements and executing a userdefined method on them
     *
     * @param callable $function the user defined function to be executed on matching elements
     */
    public function each($function) {
        for($i = $this->skip; $i < $this->limit; $i = $this->stepFunction->__invoke($i)) {
            $item = $this->list[$i];

            if(!$this->isFilterDefined() || $this->filterFunction->__invoke($item)) {
                $function($item);
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

        $this->each(function($value) use (&$array) {
            $array[] = $value;
        });

        return $array;
    }

    /**
     * method for collecting and joining all matching elements
     *
     * @param $seperator string the seperator used for streaming
     * @return string the resulting joined string
     */
    public function collect($seperator)
    {
        $joined = "";

        $this->each(function($value) use(&$joined, $seperator) {
            $joined .= (($joined != "") ? $seperator : "") . $value;
        });

        return $joined;
    }
}