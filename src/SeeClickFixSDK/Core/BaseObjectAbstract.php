<?php namespace SeeClickFixSDK\Core;

/**
 * Base object that all objects extend from
 *
 * Provides core functionality
 */
abstract class BaseObjectAbstract {

    /**
     * Object data
     *
     * @var StdClass
     * @access protected
     */
    protected $data;

    /**
     * Proxy object that does all the API heavy lifting
     *
     * @var \SeeClickFixSDK\Core\Proxy
     * @access protected
     */
    protected $proxy = null;

    /**
     * Get the ID returned from the API
     *
     * @return string
     * @access public
     */
    public function getId() {
        return $this->data->id;
    }

    /**
     * Get the API ID
     *
     * Some API objects don't have IDs
     * Those objects define their own getId() methods to return a psuedo ID
     *
     * @return string Returns the ID
     * @access public
     */
    public function getApiId() {
        return $this->getId();
    }

    /**
     * Constructor
     *
     * @param StdClass $data Object's data
     * @param \SeeClickFixSDK\Core\Proxy $proxy Object's proxy
     * @access public
     */
    public function __construct( $data, \SeeClickFixSDK\Core\Proxy $proxy = null ) {
        $this->setData( $data );
        $this->proxy = $proxy;
    }

    /**
     * Set the object's data
     *
     * @param StdClass $data Object data
     * @access public
     */
    public function setData( $data ) {
        $this->data = $data;
    }

    /**
     * Get the object's data
     *
     * @return Stdclass Returns the object's data
     * @access public
     */
    public function getData() {
        return $this->data;
    }

    /**
     * Magic method to get data
     *
     * This may be removed in future versions
     *
     * @param string $var Variable ot get from the data
     * @return mixed Returns the variable or null
     * @access public
     */
    public function __get( $var ) {
        return isset( $this->data->$var ) ? $this->data->$var : null;
    }

    /**
     * Set the object's proxy
     *
     * @param \SeeClickFixSDK\Core\Proxy $proxy Proxy object
     * @access public
     */
    public function setProxy( \SeeClickFixSDK\Core\Proxy $proxy ) {
        $this->proxy = $proxy;
    }

}
