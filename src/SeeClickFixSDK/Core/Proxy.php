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
            sprintf( '%s/places/%s', $this->api_url, $id )
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
            $this->api_url . '/places',
            $params
        );
        return $response->getRawData();
    }

    /**
     * Get current user
     *
     * @return StdClass Returns the current user data
     * @access public
     */
    public function getCurrentUser() {
        $response = $this->apiCall(
            'get',
            sprintf( '%s/profile', $this->api_url )
        );
        return $response->getRawData();
    }

    /**
     * Get issue
     *
     * @param string $id Issue ID
     * @return StdClass Returns the issue data
     * @access public
     */
    public function getIssue( $id ) {
        $response = $this->apiCall(
            'get',
            sprintf( '%s/issues/%s?details=true', $this->api_url, $id )
        );
        return $response->getRawData();
    }

    /**
     * Add a like form the current user on an issue
     *
     * @param string $issue_id Issue ID to like
     * @return StdClass Returns the status
     * @access public
     */
    public function addIssueVote( $issue_id ) {
        return $this->apiCall(
            'post',
            $this->api_url . sprintf( '/issues/%s/vote', $issue_id )
        );
    }

    /**
     * Current user follow the issue
     *
     * @param string $issue_id Issue ID to like
     * @return StdClass Returns the status
     * @access public
     */
    public function followIssue( $issue_id ) {
        $response = $this->apiCall(
            'post',
            $this->api_url . sprintf( '/issues/%s/follow', $issue_id )
        );
        dd($response);
    }

    /**
     * Get issue comments
     *
     * @param string $id Issue ID
     * @return StdClass Returns the issue data
     * @access public
     */
    public function getIssueComments( $id ) {
        $response = $this->apiCall(
            'get',
            sprintf( '%s/issues/%s/comments', $this->api_url, $id )
        );
        return $response->getRawData();
    }

    /**
     * Add a comment to an issue
     *
     * @param string $issue_id Issue ID
     * @param string $comment Comment text
     * @param string $type Comment type [comments, close, open, acknowledge]
     * @return StdClass Returns the status
     * @access public
     */
    public function addIssueComment( $issue_id, $comment, $type = 'comments' ) {
        $response = $this->apiCall(
            'post',
            $this->api_url . sprintf( '/issues/%s/%s', $issue_id, $type ),
            array( 'comment' => $comment )
        );
    }

    /**
     * Flag site content
     *
     * @param string $id Content ID
     * @param string $text Comment text
     * @param string $type Comment type [issues, comments]
     * @return StdClass Returns the status
     * @access public
     */
    public function addContentFlag( $id, $text, $type ) {
        $response = $this->apiCall(
            'post',
            $this->api_url . sprintf( '/%s/%s/flag', $type, $id ),
            array( 'text' => $text )
        );
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