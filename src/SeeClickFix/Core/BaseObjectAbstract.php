<?php namespace SeeClickFix\Core;

/**
 * Base object that all objects extend from
 *
 * Provides core functionality
 */
abstract class BaseObjectAbstract {

    /**
     * Object data
     *
     * @var object
     */
    protected $data;

    /**
     * Proxy object that does all the API heavy lifting
     *
     * @var \SeeClickFix\Core\Proxy
     */
    protected $proxy = null;

    /**
     * Get the ID returned from the API
     *
     * @return string
     */
    public function getId()
    {
        return $this->data->id;
    }

    /**
     * Get the API ID
     *
     * Some API objects don't have IDs
     * Those objects define their own getId() methods to return a psuedo ID
     *
     * @return string Returns the ID
     */
    public function getApiId()
    {
        return $this->getId();
    }

    /**
     * Constructor
     *
     * @param object $data Object's data
     * @param \SeeClickFix\Core\Proxy $proxy Object's proxy
     */
    public function __construct( $data, \SeeClickFix\Core\Proxy $proxy = null )
    {
        $this->setData( $data );
        $this->proxy = $proxy;
    }

    /**
     * Set the object's data
     *
     * @param object $data Object data
     */
    public function setData( $data )
    {
        $this->data = $data;
    }

    /**
     * Get the object's data
     *
     * @return object Returns the object's data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Magic method to get data
     *
     * This may be removed in future versions
     *
     * @param  string $var Variable ot get from the data
     * @return mixed Returns the variable or null
     */
    public function __get( $var )
    {
        return isset( $this->data->$var ) ? $this->data->$var : null;
    }

    /**
     * Set the object's proxy
     *
     * @param \SeeClickFix\Core\Proxy $proxy Proxy object
     */
    public function setProxy( \SeeClickFix\Core\Proxy $proxy )
    {
        $this->proxy = $proxy;
    }
}
