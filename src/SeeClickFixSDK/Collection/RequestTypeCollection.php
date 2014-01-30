<?php namespace SeeClickFixSDK\Collection;

/**
 * Request Type Collection
 *
 * Holds a collection of request types
 */
class RequestTypeCollection extends \SeeClickFixSDK\Collection\CollectionAbstract {

    /**
     * Request Type IDs
     *
     * @var array
     * @access protected
     */
    protected $ids = array();

    /**
     * Constructor
     *
     * @param StdClass $raw_data Object's data
     * @param \SeeClickFixSDK\Core\Proxy $proxy Object's proxy
     * @access public
     */
    public function __construct( $raw_data, \SeeClickFixSDK\Core\Proxy $proxy = null, $filterString = null ) {
        // Requesting URL
        $url = 'http://%sseeclickfix.com/api/v2/request_types/';
        $url = sprintf( $url, (\Config::get('laravel-seeclickfix-api::sandbox_mode') ? 'int.' : '') );

        // Haystack it!
        $filter = explode(',', $filterString);

        // Filter out unused request types
        foreach ($raw_data->request_types as $key=>$requestType)
        {
            $id = str_replace($url, '', $requestType->url);
            $raw_data->request_types[$key]->id = $id;

            if( $filterString && !in_array($id, $filter) ) {
                unset($raw_data->request_types[$key]);
            }
        }

        // Call parent
        parent::__construct( $raw_data, $proxy);
    }

    /**
     * Set the collection data
     *
     * @param StdClass $data
     * @access public
     */
    public function setData( $data ) {
        $this->data = $data->request_types;
        $this->convertData( '\SeeClickFixSDK\RequestType' );
    }

    /**
     * Returns the request type as an array
     *
     * @return array
     * @access public
     */
    public function toArray()
    {
        $data = array();
        foreach ($this->data as $requestType) {
            $data[] = $requestType->toArray();
        }
        return $data;
    }

}