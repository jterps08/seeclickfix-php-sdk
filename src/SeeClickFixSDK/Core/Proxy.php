<?php namespace SeeClickFixSDK\Core;

/**
 * Proxy
 *
 * This class performs all the API calls
 *
 * It uses the supplied HTTP client as a default (cURL)
 *
 */
class Proxy {

    /**
     * HTTP Client
     *
     * @var \SeeClickFixSDK\Net\ClientInterface
     * @access protected
     */
    protected $client;

    /**
     * SeeClickFix access token
     *
     * @var string
     * @access protected
     */
    protected $access_token = null;

    /**
     * Client ID
     *
     * @var string
     * @access protected
     */
    protected $client_id = null;

    /**
     * API URL
     *
     * @var string
     * @access protected
     */
    protected $api_url = 'http://test.seeclickfix.com/api/v2';

    /**
     * Constructor
     *
     * @param \SeeClickFixSDK\Net\ClientInterface $client HTTP Client
     * @param string $access_token The access token from authentication
     * @access public
     */
    public function __construct( \SeeClickFixSDK\Net\ClientInterface $client, $access_token = null ) {
        $this->client = $client;
        $this->access_token = $access_token;
    }

    /**
     * Get the access token
     *
     * @param array $data Auth data
     * @return string Returns the access token
     */
    public function getAccessToken( array $data ) {
        $response = $this->apiCall(
            'post',
            'http://test.seeclickfix.com/oauth/token',
            $data
        );
        return $response;
    }

    /**
     * Set the access token
     *
     * @param string $access_token The access token
     * @access public
     */
    public function setAccessToken( $access_token ) {
        $this->access_token = $access_token;
    }

    /**
     * Set the client ID
     *
     * @param string $client_id the client ID
     * @access public
     */
    public function setClientID( $client_id ) {
        $this->client_id = $client_id;
    }

    /**
     * Logout of SeeClickFix
     *
     * This hasn't been implemented by SeeClickFix yet
     *
     * @access public
     */
    public function logout() {
        $this->client->get( 'http://test.seeclickfix.com/api/v2/logout/', array() );
    }

    /**
     * Get user
     *
     * @param string $id User ID
     * @return StdClass Returns the user data
     * @access public
     */
    public function getUser( $id ) {
        $response = $this->apiCall(
            'get',
            sprintf( '%s/users/%s', $this->api_url, $id )
        );
        return $response->getRawData();
    }

    /**
     * Get location
     *
     * @param string $id Location ID
     * @return StdClass Returns the location data
     * @access public
     */
    public function getLocation( $id ) {
        $response = $this->apiCall(
            'get',
            sprintf( '%s/locations/%s', $this->api_url, $id )
        );
        return $response->getRawData();
    }

    /**
     * Search users
     *
     * @param array $params Search params
     * @return array Returns an array of user data
     * @access public
     */
    public function searchUsers( array $params = null ) {
        $response = $this->apiCall(
            'get',
            $this->api_url . '/users/search',
            $params
        );
        return $response->getRawData();
    }

    /**
     * Search locations
     *
     * @param array $params Search params
     * @return array Returns an array of location data
     * @access public
     */
    public function searchLocations( array $params = null ) {
        $response = $this->apiCall(
            'get',
            $this->api_url . '/locations/search',
            $params
        );
        return $response->getRawData();
    }

    /**
     * Make a call to the API
     *
     * @param string $method HTTP method to use
     * @param string $url URL
     * @param array $params API parameters
     * @param boolean $throw_exception True to throw exceptoins
     * @throws APIException, APIAuthException
     * @return  \SeeClickFixSDK\Net\ApiResponse Returns teh API response
     * @access private
     */
    private function apiCall( $method, $url, array $params = null, $throw_exception = true ){

        $raw_response = $this->client->$method(
            $url,
            array(
                'access_token'  => $this->access_token,
                'client_id'     => isset( $params['client_id'] ) ? $params['client_id'] : $this->client_id
            ) + (array) $params
        );

        $response = new \SeeClickFixSDK\Net\ApiResponse( $raw_response );

        if ( !$response->isValid() ) {
            if ( $throw_exception ) {
                if ( $response->getErrorType() == 'OAuthAccessTokenException' ) {
                    throw new \SeeClickFixSDK\Core\ApiAuthException( $response->getErrorMessage(), $response->getErrorCode(), $response->getErrorType() );
                }
                else {
                    throw new \SeeClickFixSDK\Core\ApiException( $response->getErrorMessage(), $response->getErrorCode(), $response->getErrorType() );
                }
            }
            else {
                return false;
            }
        }
        return $response;
    }


}