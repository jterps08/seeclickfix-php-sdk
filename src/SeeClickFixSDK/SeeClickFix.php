<?php namespace SeeClickFixSDK;

use \SeeClickFixSDK\Collection\UserCollection;
use \SeeClickFixSDK\User;
use \SeeClickFixSDK\Location;

/**
 * SeeClickFix!
 *
 * All objects are created through this object
 */
class SeeClickFix extends \SeeClickFixSDK\Core\BaseObjectAbstract
{
    /**
     * Configuration array
     *
     * Contains a default client and proxy
     *
     * client_id:       These three items are required for authorization
     * redirect_uri:    URL that the SeeClickFix API shoudl redirect to
     * grant_type:      Grant type from the SeeClickFix API. Only authorization_code is accepted right now.
     * display:         Pass in "touch" if you'd like your authenticating users to see a mobile-optimized
     *                  version of the authenticate page and the sign-in page.
     *
     * @var array
     * @access protected
     */
    protected $config = array(
        'client_id'     => '',
        'client_secret' => '',
        'redirect_uri'  => '',
        'grant_type'    => 'authorization_code',
        'display'       => ''
    );

    /**
     * Constructor
     *
     * @param array $config Configuration array
     * @param \SeeClickFixSDK\Net\ClientInterface $client Client object used to connect to the API
     * @access public
     */
    public function __construct( array $config = null, \SeeClickFixSDK\Net\ClientInterface $client = null ) {
        $this->config = (array) $config + $this->config;
        $this->proxy = new \SeeClickFixSDK\Core\Proxy( $client ? $client : new \SeeClickFixSDK\Net\CurlClient );
    }

    /**
     * Authorize
     *
     * Returns the SeeClickFix authorization url
     * @return string Returns the access token
     * @access public
     */
    public function getAuthorizationUri() {
        return sprintf('http://test.seeclickfix.com/oauth/authorize/?client_id=%s&redirect_uri=%s&response_type=code',
            $this->config['client_id'],
            $this->config['redirect_uri']
        );
    }

    /**
     * Get the access token
     *
     * POSTs to the SeeClickFix API and obtains and access key
     *
     * @param string $code Code supplied by SeeClickFix
     * @return string Returns the access token
     * @throws \SeeClickFixSDK\Core\ApiException
     * @access public
     */
    public function getAccessToken( $code ) {
        $post_data = array(
            'client_id'         => $this->config['client_id'],
            'client_secret'     => $this->config['client_secret'],
            'grant_type'        => $this->config['grant_type'],
            'redirect_uri'      => $this->config['redirect_uri'],
            'code'              => $code
        );
        $response = $this->proxy->getAccessToken( $post_data );
        if ( isset( $response->getRawData()->access_token ) ) {
            return $response->getRawData()->access_token;
        }
        throw new \SeeClickFixSDK\Core\ApiException( $response->getErrorMessage(), $response->getErrorCode(), $response->getErrorType() );
    }

    /**
     * Set the access token
     *
     * Most API calls require an access ID
     *
     * @param string $access_token
     * @access public
     */
    public function setAccessToken( $access_token ) {
        $this->proxy->setAccessToken( $access_token );
    }

    /**
     * Set the client ID
     *
     * Some API calls can be called with only a Client ID
     *
     * @param string $client_id Client ID
     * @access public
     */
    public function setClientID( $client_id ) {
        $this->proxy->setClientId( $client_id );
    }

    /**
     * Logout
     *
     * This doesn't actually work yet, waiting for SeeClickFix to implement it in their API
     *
     * @access public
     */
    public function logout() {
        $this->proxy->logout();
    }

    /**
     * Get user
     *
     * Retrieve a user given his/her ID
     *
     * @param int $id ID of the user to retrieve
     * @return \SeeClickFixSDK\User
     * @access public
     */
    public function getUser( $id ) {
        $user = new User( $this->proxy->getUser( $id ), $this->proxy );
        return $user;
    }

    /**
     * Get user by Username
     *
     * Retrieve a user given their username
     *
     * @param string $username Username of the user to retrieve
     * @return \SeeClickFixSDK\User
     * @access public
     * @throws \SeeClickFixSDK\ApiException
     */
    public function getUserByUsername( $username ) {
        $user = $this->searchUsers( $username, array( 'count' => 1 ) )->getItem( 0 );
        if ( $user ) {
            try {
                return $this->getUser( $user->getId() );
            } catch( \SeeClickFixSDK\Core\ApiException $e ) {
                if ( $e->getType() == $e::TYPE_NOT_ALLOWED ) {
                    return $user;
                }
            }
        }
        throw new \SeeClickFixSDK\Core\ApiException( 'username not found', 400, 'InvalidUsername' );
    }

    /**
     * Get issue
     *
     * Retreive an issue object given it's ID
     *
     * @param int $id ID of the media to retrieve
     * @return \Instagram\Media
     * @access public
     */
    public function getIssue( $id ) {
        $issue = new Issue( $this->proxy->getIssue( $id ), $this->proxy );
        return $issue;
    }

    /**
     * Get location
     *
     * Retreive a location given it's ID
     *
     * @param int $id ID of the location to retrieve
     * @return \SeeClickFixSDK\Location
     * @access public
     */
    public function getLocation( $id ) {
        $location = new Location( $this->proxy->getLocation( $id ), $this->proxy );
        return $location;
    }

    /**
     * Get current user
     *
     * Returns the current user wrapped in a CurrentUser object
     *
     * @return \SeeClickFixSDK\CurrentUser
     * @access public
     */
    public function getCurrentUser() {
        $current_user = new CurrentUser( $this->proxy->getCurrentUser(), $this->proxy );
        return $current_user;
    }

    /**
     * Search users
     *
     * Search the users by username
     *
     * @param string $query Search query
     * @param array $params Optional params to pass to the endpoint
     * @return \SeeClickFixSDK\Collection\UserCollection
     * @access public
     */
    public function searchUsers( $query, array $params = null ) {
        $params = (array)$params;
        $params['q'] = $query;
        $user_collection = new UserCollection( $this->proxy->searchUsers( $params ), $this->proxy );
        return $user_collection;
    }

}
