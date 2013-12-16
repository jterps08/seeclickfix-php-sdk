<?php namespace SeeClickFixSDK\Net;

/**
 * Curl Client
 *
 * Uses curl to access the API
 */
class CurlClient implements ClientInterface {

    /**
     * Curl Resource
     *
     * @var curl resource
     */
    protected $curl = null;

    /**
     * Constructor
     *
     * Initializes the curl object
     */
    function __construct() {
        $this->initializeCurl();
    }

    /**
     * GET
     *
     * @param string $url URL to send get request to
     * @param array $data GET data
     * @return \SeeClickFixSDK\Net\Response
     * @access public
     */
    public function get( $url, array $data = null ) {
        curl_setopt( $this->curl, CURLOPT_CUSTOMREQUEST, 'GET' );
        curl_setopt( $this->curl, CURLOPT_URL, sprintf( "%s?%s", $url, http_build_query( $data ) ) );
        return $this->fetch();
    }

    /**
     * POST
     *
     * @param string $url URL to send post request to
     * @param array $data POST data
     * @return \SeeClickFixSDK\Net\Response
     * @access public
     */
    public function post( $url, array $data = null ) {
        curl_setopt( $this->curl, CURLOPT_CUSTOMREQUEST, 'POST' );
        curl_setopt( $this->curl, CURLOPT_URL, $url );
        curl_setopt( $this->curl, CURLOPT_POST, 1);
        curl_setopt( $this->curl, CURLOPT_POSTFIELDS, $this->http_build_query_for_curl($data) );

        // curl_setopt( $this->curl, CURLOPT_RETURNTRANSFER, true );
        // curl_setopt( $this->curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8","Accept:application/json, text/javascript, */*; q=0.01"));
        // curl_setopt( $this->curl, CURLOPT_POSTFIELDS, json_encode( $data ) );
        return $this->fetch();
    }

    /**
     * PUT
     *
     * @param string $url URL to send put request to
     * @param array $data PUT data
     * @return \SeeClickFixSDK\Net\Response
     * @access public
     */
    public function put( $url, array $data = null  ) {
        curl_setopt( $this->curl, CURLOPT_CUSTOMREQUEST, 'PUT' );
    }

    /**
     * DELETE
     *
     * @param string $url URL to send delete request to
     * @param array $data DELETE data
     * @return \SeeClickFixSDK\Net\Response
     * @access public
     */
    public function delete( $url, array $data = null  ) {
        curl_setopt( $this->curl, CURLOPT_URL, sprintf( "%s?%s", $url, http_build_query( $data ) ) );
        curl_setopt( $this->curl, CURLOPT_CUSTOMREQUEST, 'DELETE' );
        return $this->fetch();
    }

    /**
     * Initialize curl
     *
     * Sets initial parameters on the curl object
     *
     * @access protected
     */
    protected function initializeCurl() {
        $this->curl = curl_init();
        curl_setopt( $this->curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $this->curl, CURLOPT_SSL_VERIFYPEER, false );
        //curl_setopt( $this->curl, CURLOPT_HTTPHEADER, array('User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:19.0) Gecko/20100101 Firefox/19.0') );
    }

    /**
     * Fetch
     *
     * Execute the curl object
     *
     * @return StdClass
     * @access protected
     * @throws \SeeClickFixSDK\Core\ApiException
     */
    protected function fetch() {
        $raw_response = curl_exec( $this->curl );
        $error = curl_error( $this->curl );
        if ( $error ) {
            throw new \SeeClickFixSDK\Core\ApiException( $error, 666, 'CurlError' );
        }
        return $raw_response;
    }

    /**
     * Handle nested arrays when posting
     *
     * @return array
     * @access protected
     */
    protected function http_build_query_for_curl(array $var, $prefix = false) {
        $return = array();

        foreach($var as $idx => $value) {
            if(is_scalar($value)) {
                if($prefix) {
                    $return[$prefix.'['.$idx.']'] = $value;
                }
                else {
                    $return[$idx] = $value;
                }
            }
            else if(gettype($value) === 'array') {
                $return = array_merge($return, $this->http_build_query_for_curl($value, $prefix ? $prefix.'['.$idx.']' : $idx));
            }
        }

        return $return;
    }
}