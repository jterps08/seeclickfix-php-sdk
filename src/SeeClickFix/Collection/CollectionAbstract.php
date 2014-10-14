<?php namespace SeeClickFix\Collection;

/**
 * Abstract Collection
 *
 * All Collections extend this class
 */
abstract class CollectionAbstract implements \IteratorAggregate, \ArrayAccess, \Countable {

    /**
     * Holds the pagination data for the collection
     *
     * @var object
     */
    protected $pagination;

    /**
     * Holds the data for the collection
     *
     * @var array
     */
    protected $data = array();

    /**
     * Holds the position for the iterator
     *
     * @var integer
     */
    protected $position;

    /**
     * Constructor
     *
     * Sets the data and child object's proxies
     *
     * @param  object $data Data from the API
     * @param  \SeeClickFix\Core\Proxy $proxy Proxy to pass on to teh collection's objects
     */
    public function __construct($data = null, \SeeClickFix\Core\Proxy $proxy = null)
    {
        if ($data) {
            $this->setData($data);
        }

        if ($proxy) {
            $this->setProxies($proxy);
        }
    }

    /**
     * Set the collections data
     *
     * @param object $data Data from the API
     */
    public abstract function setData($data);

    /**
     * Get the collection's data
     *
     * @return object
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get a collection item
     *
     * @param  int    $position Item to retrieve, starting at 0
     * @return mixed  Returns the collection item at the position
     */
    public function getItem($position)
    {
        return isset($this->data[$position]) ? $this->data[$position] : null;
    }

    /**
     * Get a slice of the collection
     *
     * @param  int   $offset Where to start the slice
     * @param  int   $length Length of the slice
     * @return array Returns a slice of the array
     */
    public function getSlice($offset, $length)
    {
        return array_slice($this->data, $offset, $length);
    }

    /**
     * Add data
     *
     * Add data from another collection the this collection
     *
     * @param \SeeClickFix\Collection\CollectionAbstract $object Object to add the data of
     */
    public function addData(\SeeClickFix\Collection\CollectionAbstract $object)
    {
        $this->data = array_merge($this->data, $object->getData());
    }

    /**
     * Convert the collection's objects
     *
     * Child classes use this to turn the objects into the correct class
     *
     * @param string $object
     */
    protected function convertData($object)
    {
        $this->data = array_map(
            function($c) use($object)
            {
                if(!isset($c->moderated) || $c->moderated === false) {
                    return new $object($c);
                }
            },
            $this->data
        );
    }

    /**
     * Set object proxies
     *
     * Sets all the child object's proxies
     *
     * @param \SeeClickFix\Core\Proxy $proxy
     */
    public function setProxies(\SeeClickFix\Core\Proxy $proxy)
    {
        foreach($this->data as $object) {
            $object->setProxy($proxy);
        }
    }

    /**
     * Implode the collection
     *
     * Implode the collection into a string
     *
     * Example - Get a media's tags into a comma delimited string
     *
     * $media->getTags()->implode(
     *     function($t){ return sprintf('<a href="?example=tag.php&tag=%1$s">#%1$s</a>', $t); }
     * )
     *
     * @param  \Closure $callback Function to run on the collection
     * @param  string $sep Implode separator
     * @return array
     */
    public function implode(\Closure $callback = null, $sep = ', ')
    {
        if (! count($this->getData())) {
            return null;
        }

        if (! $callback) {
            $callback = function($i) { return $i->__toString(); };
        }

        foreach($this->getData() as $item) {
            $items[] = $callback($item);
        }

        return implode($sep, $items);
    }

    /**
     * IteratorAggregate
     *
     * {@link http://us2.php.net/manual/en/class.iteratoraggregate.php}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * ArrayAccess
     *
     * {@link http://us2.php.net/manual/en/class.arrayaccess.php}
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        //trigger_error("You can't set collection data");
    }

    public function offsetUnset($offset)
    {
        trigger_error("You can't unset collection data");
    }

    /**
     * Countable
     *
     * {@link http://us2.php.net/manual/en/class.countable.php}
     */
    public function count()
    {
        return count($this->data);
    }


    /**
     * Get an array with the values of a given column.
     *
     * @param  string  $column
     * @return array
     */
    public function lists($column)
    {
        $data = array();

        foreach ($this->data as $key => $value)
        {
            $data[] = $value->$column;
        }

        return $data;
    }
}
