<?php namespace SeeClickFixSDK\Net;

use Guzzle\Http\Client;

/**
 * Guzzle Client
 *
 * Uses Guzzle to access the API
 */
class GuzzleClient {

    /**
     * Guzzle Resource
     *
     * @var guzzle Resource
     */
    protected $guzzle = null;

    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
        $this->guzzle = new Client;
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
        $request = $this->guzzle->get(sprintf( "%s?%s", $url, http_build_query( $data ) ));
        $response = $request->send();

        return $response->getBody();
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
        $request = $this->guzzle->post($url, array(), $data);
        $response = $request->send();

        return $response->getBody();
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
        $request = $this->guzzle->put($url, null, http_build_query( $data ))->send();
        $response = $request->send();

        return $response->getBody();
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
        $request = $this->guzzle->delete(sprintf( "%s?%s", $url, http_build_query( $data ) ));
        $response = $request->send();

        return $response->getBody();
    }

}
