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
     * @var \SeeClickFixSDK\Net\GuzzleClient
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
    protected $api_url = 'http://%sseeclickfix.com/api/v2';

    /**
     * oAuth Token URL
     *
     * @var string
     * @access protected
     */
    protected $token_url = 'http://%sseeclickfix.com/oauth/token';

    /**
     * Constructor
     *
     * @param string $client_id Client Id given by SeeClickFix
     * @access public
     */
    public function __construct($client_id = null, $sandbox = false )
    {
        $this->client = new \SeeClickFixSDK\Net\GuzzleClient;
        $this->client_id = $client_id;

        // Sandbox mode?
        $this->api_url = sprintf( $this->api_url, ($sandbox ? 'test.' : '') );
        $this->token_url = sprintf( $this->token_url, ($sandbox ? 'test.' : '') );
    }

    /**
     * Get the access token
     *
     * @param array $data Auth data
     * @return string Returns the access token
     */
    public function getAccessToken( array $data )
    {
        $response = $this->apiCall(
            'post',
            $this->token_url,
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
    public function setAccessToken( $access_token )
    {
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
    public function logout()
    {
        $this->client->get( $this->api_url . '/logout/', array() );
    }

    /**
     * Get user
     *
     * @param string $id User ID
     * @return StdClass Returns the user data
     * @access public
     */
    public function getUser( $id )
    {
        $response = $this->apiCall(
            'get',
            sprintf( '%s/users/%s', $this->api_url, $id )
        );
        return $response->getRawData();
    }

    /**
     * Get place
     *
     * @param string $id Place ID
     * @return StdClass Returns the place data
     * @access public
     */
    public function getPlace( $id )
    {
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
    public function searchUsers( array $params = null )
    {
        $response = $this->apiCall(
            'get',
            $this->api_url . '/users',
            $params
        );
        return $response->getRawData();
    }

    /**
     * Search places
     *
     * @param array $params Search params
     * @return array Returns an array of place data
     * @access public
     */
    public function searchPlaces( array $params = null )
    {
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
    public function getCurrentUser()
    {
        $response = $this->apiCall(
            'get',
            sprintf( '%s/profile', $this->api_url )
        );
        return $response->getRawData();
    }

    /**
     * Get list of issues in a place
     *
     * @param array $params Search params
     * @return StdClass Returns the issue object
     * @access public
     */
    public function getIssues( array $params = null )
    {
        $response = $this->apiCall(
            'get',
            $this->api_url . '/issues',
            $params
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
    public function getIssue( $id )
    {
        $response = $this->apiCall(
            'get',
            sprintf( '%s/issues/%s?details=true', $this->api_url, $id )
        );
        return $response->getRawData();
    }

    /**
     * Get location's request types
     *
     * @param string $point Required location parameters
     * @return object
     * @access public
     */
    public function getLocationRequestTypes( $point )
    {
        $response = $this->apiCall(
            'get',
            $this->api_url . '/issues/new',
            $point
        );
        return $response->getRawData();
    }

    /**
     * Get issue
     *
     * @param string $point Required location parameters
     * @return object
     * @access public
     */
    public function getRequestType( $id )
    {
        $response = $this->apiCall(
            'get',
            $this->api_url . '/request_types/' . $id
        );
        return $response->getRawData();
    }

    /**
     * Create an issue
     *
     * @param string $params Required parameters
     * @return StdClass Returns metadata
     * @access public
     */
    public function createIssue( $params )
    {
        $response = $this->apiCall(
            'post',
            $this->api_url . '/issues',
            $params
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
    public function addIssueVote( $issue_id )
    {
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
    public function followIssue( $issue_id )
    {
        return $this->apiCall(
            'post',
            $this->api_url . sprintf( '/issues/%s/follow', $issue_id )
        );
    }

    /**
     * Get issue comments
     *
     * @param string $id Issue ID
     * @return StdClass Returns the issue data
     * @access public
     */
    public function getIssueComments( $id )
    {
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
     * @param array $params Optional parameters
     * @return StdClass Returns the status
     * @access public
     */
    public function addIssueComment( $issue_id, $comment, array $params = null )
    {
        $params = array_merge([
            'status' => 'comments',
            'comment' => $comment
        ], (array)$params );

        return $this->apiCall(
            'post',
            $this->api_url . sprintf( '/issues/%s/%s', $issue_id, $params['status'] ),
            $params
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
    public function addContentFlag( $id, $text, $type )
    {
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
    private function apiCall( $method, $url, array $params = null, $throw_exception = true )
    {
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
