<?php namespace SeeClickFixSDK\Net;

use Guzzle\Http\StaticClient as Guzzle;
use Guzzle\Http\Client;

/**
 * Guzzle Client
 *
 * Uses Guzzle to access the API
 */
class GuzzleClient {

    /**
     * GET
     *
     * @param string $url URL to send get request to
     * @param array $data GET data
     * @return \SeeClickFixSDK\Net\Response
     * @access public
     */
    public function get( $url, array $data = null ) {
        $response = Guzzle::get(sprintf( "%s?%s", $url, http_build_query( $data ) ), array('Accept' => 'application/json'));
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
    public function post( $url, array $data = null ) {
        $response = Guzzle::post($url, [
            'body'   => $data
        ]);
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
    public function put( $url, array $data = null  ) {
        $response = $client->put($url, null, http_build_query( $data ))->send();
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
    public function delete( $url, array $data = null  ) {
        $response = Guzzle::delete(sprintf( "%s?%s", $url, http_build_query( $data ) ));
        return $response->getBody();
    }
}
