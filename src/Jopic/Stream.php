<?php
/**
 * Class for creating List and Array Streams
 *
 * @package Jopic
 * @author  Johannes Pichler <johannes.pichler@jopic.at>
 */

namespace Jopic;

/**
 * Class Stream
 */
class Stream
{
    /**
     * method for instantiating a ListStream
     *
     * @param $list array the actual list to stream (array with numeric indices
     * @return Streams\ListStream an instance of @see ListStream
     */
    public static function ofList($list) {
        return new Streams\ListStream($list);
    }

    /**
     * method for instantiating a ArrayStream
     *
     * @param $array array the actual array to stream
     * @return Streams\ArrayStream an instance of @see ArrayStream
     */
    public static function ofArray($array) {
        return new Streams\ArrayStream($array);
    }
}