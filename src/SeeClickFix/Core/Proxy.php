<?php namespace SeeClickFix\Core;

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
     * @var \SeeClickFix\Net\CurlClient
     */
    protected $client;

    /**
     * SeeClickFix access token
     *
     * @var string
     */
    protected $access_token = null;

    /**
     * Client ID
     *
     * @var string
     */
    protected $client_id = null;

    /**
     * Base URL
     *
     * @var string
     */
    protected $base_url = 'https://%sseeclickfix.com';

    /**
     * API URL
     *
     * @var string
     */
    protected $api_url = '/api/v2';

    /**
     * oAuth Token URL
     *
     * @var string
     */
    protected $token_url = '/oauth/token';

    /**
     * Constructor
     *
     * @param string $client_id Client Id given by SeeClickFix
     */
    public function __construct($client_id = null, $sandbox = false )
    {
        $this->client    = new \SeeClickFix\Net\CurlClient;
        $this->client_id = $client_id;

        // Set base URL
        $this->base_url = sprintf( $this->base_url, ($sandbox ? 'test.' : '') );

        // Set URLs
        $this->api_url   = $this->base_url . $this->api_url;
        $this->token_url = $this->base_url . $this->token_url;
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
     */
    public function setAccessToken( $access_token )
    {
        $this->access_token = $access_token;
    }

    /**
     * Get the access token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->access_token;
    }

    /**
     * Set the client ID
     *
     * @param string $client_id the client ID
     */
    public function setClientID( $client_id ) {
        $this->client_id = $client_id;
    }

    /**
     * Logout of SeeClickFix
     *
     * This hasn't been implemented by SeeClickFix yet
     */
    public function logout()
    {
        $this->client->get( $this->api_url . '/logout/', array() );
    }

    /**
     * Update current user user
     *
     * @param  array $params Update params
     * @return object Returns the user data
     */
    public function updateCurrentUser( array $params = null )
    {
        $response = $this->apiCall(
            'put',
            $this->api_url . '/profile',
            $params
        );

        return $response->getRawData();
    }

    /**
     * Get user
     *
     * @param  string $id User ID
     * @return object Returns the user data
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
     * Create a user
     *
     * @param  array $params Registration parameters
     * @return object Returns the user data
     */
    public function createUser( array $params = null )
    {
        $response = $this->apiCall(
            'post',
            $this->api_url . '/users',
            $params
        );
        return $response->getRawData();
    }

    /**
     * Get place
     *
     * @param  string $id Place ID
     * @return object Returns the place data
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
     * @param  array $params Search params
     * @return array Returns an array of user data
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
     * @param  array $params Search params
     * @return array Returns an array of place data
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
     * @return object Returns the current user data
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
     * @param  array $params Search params
     * @return object Returns the issue object
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
     * @param  string $id Issue ID
     * @return object Returns the issue data
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
     * @param  string $point Required location parameters
     * @return object
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
     * @param  string $id
     * @return object
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
     * @param  string $params Required parameters
     * @return object Returns metadata
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
     * @param  string $issue_id Issue ID to like
     * @return object Returns the status
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
     * @param  string $issue_id Issue ID to like
     * @return object Returns the status
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
     * @param  string $id Issue ID
     * @return object Returns the issue data
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
     * @param  string $issue_id Issue ID
     * @param  string $comment Comment text
     * @param  array $params Optional parameters
     * @return object Returns the status
     */
    public function addIssueComment( $issue_id, $comment, array $params = null )
    {
        $params = array_merge([
            'status' => 'comments',
            'comment' => $comment
        ], (array)$params );

        $response = $this->apiCall(
            'post',
            $this->api_url . sprintf( '/issues/%s/%s', $issue_id, $params['status'] ),
            $params
        );

        return $response->getRawData();
    }

    /**
     * Flag site content
     *
     * @param  string $id Content ID
     * @param  string $text Comment text
     * @param  string $type Comment type [issues, comments]
     * @return object Returns the status
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
     * @param  string $method HTTP method to use
     * @param  string $url URL
     * @param  array $params API parameters
     * @param  boolean $throw_exception True to throw exceptoins
     * @throws APIException, APIAuthException
     * @return \SeeClickFix\Net\ApiResponse Returns teh API response
     */
    private function apiCall( $method, $url, array $params = null, $throw_exception = true )
    {
        $raw_response = $this->client->$method(
            $url,
            array(
                'access_token'  => $this->access_token,
                'client_id'     => $this->client_id
            ) + (array) $params
        );

        $response = new \SeeClickFix\Net\ApiResponse( $raw_response );

        if ( !$response->isValid() )
        {
            if ( $throw_exception )
            {
                if ( $response->getErrorType() == 'OAuthAccessTokenException' ) {
                    throw new \SeeClickFix\Core\ApiAuthException( $response->getErrorMessage(), $response->getErrorCode(), $response->getErrorType() );
                }
                else {
                    throw new \SeeClickFix\Core\ApiException( $response->getErrorMessage(), $response->getErrorCode(), $response->getErrorType() );
                }
            }
            else {
                return false;
            }
        }

        return $response;
    }
}
