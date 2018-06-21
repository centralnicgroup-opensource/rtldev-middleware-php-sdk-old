<?php
declare(strict_types=1);

/**
 * HEXONET
 * Copyright © HEXONET
 */

namespace HEXONET;

use ArrayAccess;
use Iterator;
use Countable;

/**
 * HEXONET Response
 *
 * @package HEXONET
 */
class Response implements ArrayAccess, Iterator, Countable
{

    /**
     * Iterator for the Iterator class
     * @var int
     */
    private $iterator_position;
    
    /**
     * Contains the response as a string
     * @var string
     */
    private $response_string;
    
    /**
     * Contains the response as a hash
     * @var array
     */
    private $response_hash;
    
    /**
     * Contains the response as a list hash
     * @var array
     */
    private $response_list_hash;

    /**
     * Constructor
     *
     * @param Response $response A response as a string
     */
    public function __construct($response)
    {
        $this->iterator_position = 0;
        $this->response_string = $response;
    }
    
    /**
     * Returns the response as a string
     *
     * @return string The response as a sting
     */
    public function asString()
    {
        return $this->response_string;
    }
    
    /**
     * Returns the response as a hash
     *
     * @return array The response as a hash
     */
    public function asHash()
    {
        if (!isset($this->response_hash)) {
            $this->response_hash = Util::responseToHash($this->response_string);
        }
        return $this->response_hash;
    }
    
    /**
     * Returns the response as a list hash
     *
     * @return array The response as a list hash
     */
    public function asListHash()
    {
        if (!isset($this->response_list_hash)) {
            $this->response_list_hash = Util::responseToListHash($this->asHash());
        }
        return $this->response_list_hash;
    }
    
    /**
     * Returns the response as a list
     *
     * @return array The response as a list
     */
    public function asList()
    {
        $list_hash = $this->asListHash();
        return $list_hash["ITEMS"];
    }
    
    /**
     * Returns the response code
     *
     * @return int The response code
     */
    public function code()
    {
        return $this->__get("CODE");
    }
    
    /**
     * Returns the response description
     *
     * @return string The response description
     */
    public function description()
    {
        return $this->__get("DESCRIPTION");
    }
    
    /**
     * Returns the response properties
     *
     * @return array The response properties
     */
    public function properties()
    {
        return $this->__get("PROPERTY");
    }
    
    /**
     * Returns the response runtime
     *
     * @return float The response runtime
     */
    public function runtime()
    {
        return $this->__get("RUNTIME");
    }
    
    /**
     * Returns the response queutime
     *
     * @return float The response queutime
     */
    public function queuetime()
    {
        return $this->__get("QUEUETIME");
    }
    
    /**
     * Returns the property for a given index
     * If no index given, the complete property list is returned
     *
     * @param int $index The index of the property
     * @return array A proterty
     */
    public function property($index = null)
    {
        $properties = $this->asList();
        if (is_int($index)) {
            return isset($properties[$index]) ? $properties[$index] : null;
        } else {
            return $properties;
        }
    }
    
    /**
     * Returns true if the results is a success
     * Success = response code starting with 2
     *
     * @return boolean
     */
    public function isSuccess()
    {
        return preg_match('/^2/', $this->code()) ? true : false;
    }
    
    /**
     * Returns true if the results is a tmp error
     * tmp error = response code starting with 4
     *
     * @return boolean
     */
    public function isTmpError()
    {
        return preg_match('/^4/', $this->code()) ? true : false;
    }
    
    /**
     * Operator overloading
     * Example: __get("code") == $response->code
     *
     * @param string $name An array key
     */
    public function __get($name)
    {
        $hash = $this->asHash();
        if (array_key_exists(strtoupper($name), $hash)) {
            return $hash[strtoupper($name)];
        }
        return null;
    }
    

    /**
     * ArrayAccess : offsetSet not implemented
     *
     * @param int $offset An array key
     * @param string $value A value
     */
    public function offsetSet($offset, $value)
    {
        //NOT IMPLEMENTED
    }
    
    /**
     * ArrayAccess : offsetExists
     * Return true if the array key exists
     *
     * @param int $offset An array key
     */
    public function offsetExists($offset)
    {
        if (preg_match('/^[0-9]+$/', $offset . "")) {
            $list = $this->asList();
            return isset($list[$offset]);
        }
        $hash = $this->asListHash();
        return isset($hash[strtoupper($offset)]);
    }
    
    /**
     * ArrayAcess : offsetUnset not implemented
     *
     * @param int $offset An array key
     */
    public function offsetUnset($offset)
    {
        //NOT IMPLEMENTED
    }
    
    /**
     * ArrayAccess : offsetGet
     * Return the array for the given offset
     *
     * @param int $offset An array key
     */
    public function offsetGet($offset)
    {
        if (preg_match('/^[0-9]+$/', $offset . "")) {
            $list = $this->asList();
            return $list[$offset];
        }
        $hash = $this->asListHash();
        return isset($hash[strtoupper($offset)]) ? $hash[strtoupper($offset)] : null;
    }
    
    /**
     * Returns the columns
     *
     * @return array The columns
     */
    public function columns()
    {
        $list_hash = $this->asListHash();
        return $list_hash["COLUMNS"];
    }
    
    /**
     * Returns the index of the first element
     *
     * @return int The index of the first element
     */
    public function first()
    {
        $list_hash = $this->asListHash();
        return $list_hash["FIRST"];
    }
    
    /**
     * Returns the index of the last element
     *
     * @return int The index of the last element
     */
    public function last()
    {
        $list_hash = $this->asListHash();
        return $list_hash["LAST"];
    }
    
    /**
     * Returns the number of list elements returned (= last - first + 1)
     *
     * @return int The number of list elements returned
     */
    public function count()
    {
        $list_hash = $this->asListHash();
        return $list_hash["COUNT"];
    }
    
    /**
     * Returns the limit of the response
     *
     * @return int The limit
     */
    public function limit()
    {
        $list_hash = $this->asListHash();
        return $list_hash["LIMIT"];
    }
    
    /**
     * Returns the total number of elements found (!= count)
     *
     * @return int The total number of elements found
     */
    public function total()
    {
        $list_hash = $this->asListHash();
        return $list_hash["TOTAL"];
    }
    
    /**
     * Returns the number of pages
     *
     * @return int The number of pages
     */
    public function pages()
    {
        $list_hash = $this->asListHash();
        return $list_hash["PAGES"];
    }
    
    /**
     * Returns the number of the current page (starts with 1)
     *
     * @return int The number of the current page
     */
    public function page()
    {
        $list_hash = $this->asListHash();
        return $list_hash["PAGE"];
    }
    
    /**
     * Returns the number of the previous page
     *
     * @return int The number of the previous page
     */
    public function prevpage()
    {
        $list_hash = $this->asListHash();
        return isset($list_hash["PREVPAGE"]) ? $list_hash["PREVPAGE"] : null;
    }
    
    /**
     * Returns the first index for the previous page
     *
     * @return int The first index of the previous page
     */
    public function prevpagefirst()
    {
        $list_hash = $this->asListHash();
        return isset($list_hash["PREVPAGEFIRST"]) ? $list_hash["PREVPAGEFIRST"] : null;
    }
    
    /**
     * Returns the number of the next page
     *
     * @return int The number of the next page
     */
    public function nextpage()
    {
        $list_hash = $this->asListHash();
        return array_key_exists("NEXTPAGE", $list_hash) ? $list_hash["NEXTPAGE"] : null;
    }
    
    /**
     * Returns the first index for the next page
     *
     * @return int The first index of the next page
     */
    public function nextpagefirst()
    {
        $list_hash = $this->asListHash();
        return array_key_exists("NEXTPAGEFIRST", $list_hash) ? $list_hash["NEXTPAGEFIRST"] : null;
    }
    
    /**
     * Returns the first index for the last page
     *
     * @return int The first index of the last page
     */
    public function lastpagefirst()
    {
        $list_hash = $this->asListHash();
        return array_key_exists("LASTPAGEFIRST", $list_hash) ? $list_hash["LASTPAGEFIRST"] : null;
    }
    
    /**
     * Iterator : rewind
     * Set the iterator to 0
     *
     * @return array The first element of the list
     */
    public function rewind()
    {
        $this->iterator_position = 0;
        return $this->property(0);
    }
    
    /**
     * Iterator : current
     * Returns the current element of the list
     *
     * @return array The current element of the list
     */
    public function current()
    {
        return $this->property($this->iterator_position);
    }
    
    /**
     * Iterator : key
     * Returns the key of the current element
     *
     * @return int The key of the current element
     */
    public function key()
    {
        return $this->iterator_position;
    }
    
    /**
     * Iterator : next
     * Returns the key of the next element
     *
     * @return int The key of the next element
     */
    public function next()
    {
        ++$this->iterator_position;
    }
    
    /**
     * Iterator : valid
     * Returns the element if it exists
     *
     * @return array The element if it exists
     */
    public function valid()
    {
        return $this->property($this->iterator_position);
    }
}
