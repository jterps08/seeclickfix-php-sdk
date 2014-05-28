<?php namespace SeeClickFixSDK\Net;

/**
 * Curl Client
 *
 * Uses curl to access the API
 */
class CurlClient {

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
    function __construct()
    {
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
    public function get( $url, array $data = null )
    {
        curl_setopt( $this->curl, CURLOPT_HTTPGET, 1);
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
    public function post( $url, array $data = null )
    {
        curl_setopt( $this->curl, CURLOPT_CUSTOMREQUEST, 'POST' );
        curl_setopt( $this->curl, CURLOPT_URL, $url );
        curl_setopt( $this->curl, CURLOPT_POST, 1);
        curl_setopt( $this->curl, CURLOPT_POSTFIELDS, $this->http_build_query_for_curl($data) );
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
    public function put( $url, array $data = null  )
    {
        curl_setopt( $this->curl, CURLOPT_CUSTOMREQUEST, 'PUT' );
        curl_setopt( $this->curl, CURLOPT_URL, $url );
        curl_setopt( $this->curl, CURLOPT_POST, 1);
        curl_setopt( $this->curl, CURLOPT_POSTFIELDS, $this->http_build_query_for_curl($data) );
        return $this->fetch();
    }

    /**
     * DELETE
     *
     * @param string $url URL to send delete request to
     * @param array $data DELETE data
     * @return \SeeClickFixSDK\Net\Response
     * @access public
     */
    public function delete( $url, array $data = null  )
    {
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
    protected function initializeCurl()
    {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
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
    protected function fetch()
    {
        $raw_response = curl_exec( $this->curl );
        $error = curl_error( $this->curl );

        if ( $error )
        {
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
    protected function http_build_query_for_curl(array $var, $prefix = false)
    {
        $return = array();

        foreach($var as $idx => $value)
        {
            if(is_scalar($value))
            {
                // Add uploads
                if (is_string($value) && substr($value, 0, 1) == '@') {
                    $value = $this->addPostFile($value);
                }

                if($prefix) {
                    $return[$prefix.'['.$idx.']'] = $value;
                }
                else {
                    $return[$idx] = $value;
                }
            }
            else if(gettype($value) === 'array')
            {
                $return = array_merge($return, $this->http_build_query_for_curl($value, $prefix ? $prefix.'['.$idx.']' : $idx));
            }
        }

        return $return;
    }

    /**
     * POST file upload
     *
     * @param string $filename File to be uploaded
     * @return mix
     * @access protected
     */
    public function addPostFile($filename)
    {
        // Remove leading @ symbol
        if (strpos($filename, '@') === 0) {
            $filename = substr($filename, 1);
        }

        if (!is_readable($filename)) {
            throw new InvalidArgumentException("Unable to open {$filename} for reading");
        }

        // PHP 5.5 introduced a CurlFile object that deprecates the old @filename syntax
        // See: https://wiki.php.net/rfc/curl-file-upload
        if (function_exists('curl_file_create')) {
            return curl_file_create($filename);
        }

        // Use the old style if using an older version of PHP
        return "@{$filename}";
    }
}
