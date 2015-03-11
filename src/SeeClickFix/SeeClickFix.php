<?php namespace SeeClickFix;

use \SeeClickFix\Collection\UserCollection;
use \SeeClickFix\Collection\PlaceCollection;
use \SeeClickFix\Collection\IssueCollection;
use \SeeClickFix\Collection\RequestTypeCollection;
use \SeeClickFix\User;
use \SeeClickFix\Place;

/**
 * SeeClickFix!
 *
 * All objects are created through this object
 */
class SeeClickFix extends \SeeClickFix\Core\BaseObjectAbstract
{
    /**
     * The user that's been retrieved and is used
     * for authentication. Authentication methods
     * are available for finding the user to set
     * here.
     *
     * @var \SeeClickFix\User
     */
    protected $current_user;

    /**
     * Configuration array
     *
     * Contains a default client and proxy
     *
     * client_id:       These three items are required for authorization
     * redirect_uri:    URL that the SeeClickFix API shoudl redirect to
     * display:         Pass in "touch" if you'd like your authenticating users to see a mobile-optimized
     *                  version of the authenticate page and the sign-in page.
     *
     * @var array
     */
    protected $config = array(
        'client_id'     => '',
        'client_secret' => '',
        'redirect_uri'  => '',
        'display'       => '',
        'sandbox'       => false
    );

    /**
     * Constructor
     *
     * @param array $config Configuration array
     */
    public function __construct( array $config = null )
    {
        $this->config = (array) $config + $this->config;
        $this->proxy = new \SeeClickFix\Core\Proxy($this->config['client_id'], $this->config['sandbox']);
    }

    /**
     * Authorize
     *
     * Returns the SeeClickFix authorization url
     * @return string Returns the access token
     */
    public function getAuthorizationUri()
    {
        return sprintf('http://%sseeclickfix.com/oauth/authorize/?client_id=%s&redirect_uri=%s&response_type=code',
            ($this->config['sandbox'] ? 'test.' : ''),
            $this->config['client_id'],
            $this->config['redirect_uri']
        );
    }

    /**
     * Get the access token
     *
     * POSTs to the SeeClickFix API and obtains and access key
     *
     * @param  string $params Username/Password or Code
     * @return string Returns the access token
     * @throws \SeeClickFix\Core\ApiException
     */
    public function getAccessToken( $params )
    {
        $post_data = array(
            'client_id'         => $this->config['client_id'],
            'client_secret'     => $this->config['client_secret'],
            'grant_type'        => isset($params['grant_type']) ? $params['grant_type'] : 'authorization_code'
        );

        // What type of grant is it?
        if($post_data['grant_type'] === 'password')
        {
            $post_data['username'] = $params['username'];
            $post_data['password'] = $params['password'];
        }
        else {
            $post_data['redirect_uri'] = $this->config['redirect_uri'];
            $post_data['code']         = $params['code'];
        }

        $response = $this->proxy->getAccessToken( $post_data );

        if ( isset( $response->getRawData()->access_token ) )
        {
            return $response->getRawData()->access_token;
        }

        throw new \SeeClickFix\Core\ApiException(
                    $response->getErrorMessage(),
                    $response->getErrorCode(),
                    $response->getErrorType()
        );
    }

    /**
     * Set the access token
     *
     * Most API calls require an access ID
     *
     * @param string $access_token
     */
    public function setAccessToken( $access_token )
    {
        $this->proxy->setAccessToken( $access_token );
    }

    /**
     * Return the access token
     *
     * @return string $access_token
     */
    public function getToken()
    {
        return $this->proxy->getToken();
    }

    /**
     * Set the client ID
     *
     * Some API calls can be called with only a Client ID
     *
     * @param string $client_id Client ID
     */
    public function setClientID( $client_id )
    {
        $this->proxy->setClientId( $client_id );
    }

    /**
     * Logout
     *
     * This doesn't actually work yet, waiting for SeeClickFix to implement it in their API
     */
    public function logout()
    {
        $this->proxy->logout();
    }

    /**
     * Get a list of users
     *
     * Retrieve a list of users
     *
     * @param  array $params Search params
     * @return \SeeClickFix\User
     */
    public function getUsers( array $params = null )
    {
        $params = (array)$params;
        $user_collection = new UserCollection( $this->proxy->searchUsers( $params ), $this->proxy );
        return $user_collection;
    }

    /**
     * Get user
     *
     * Retrieve a user given his/her ID
     *
     * @param  int $id ID of the user to retrieve
     * @return \SeeClickFix\User
     */
    public function getUser( $id )
    {
        $user = new User( $this->proxy->getUser( $id ), $this->proxy );
        return $user;
    }

    /**
     * Reset a user's password
     *
     * @param  array $email User email
     * @return \SeeClickFix\User
     */
    public function resetPassword( $email )
    {
        return $this->proxy->resetPassword( $email );
    }

    /**
     * Create a user
     *
     * Register a user on SeeClickFix
     *
     * @param  array $params Registration parameters
     * @return \SeeClickFix\User
     */
    public function createUser( array $params = null )
    {
        $user = new User( $this->proxy->createUser( $params ), $this->proxy );
        return $user;
    }

    /**
     * Get issues this place
     *
     * @param  array $params Search params
     * @return \SeeClickFix\IssueCollection
     */
    public function getIssues( array $params = null )
    {
        $params = (array)$params;
        $issues = new IssueCollection( $this->proxy->getIssues( $params ), $this->proxy );
        return $issues;
    }

    /**
     * Get issue
     *
     * Retreive an issue object given it's ID
     *
     * @param int $id ID of the media to retrieve
     * @return \SeeClickFix\Issue
     */
    public function getIssue( $id )
    {
        $issue = new Issue( $this->proxy->getIssue( $id ), $this->proxy );
        return $issue;
    }

    /**
     * Get location request types
     *
     * Retreive request types based on location
     *
     * @param  string $point Location parameters
     * @return object
     */
    public function getLocationRequestTypes( $point, $filter = null )
    {
        return new RequestTypeCollection($this->proxy->getLocationRequestTypes( $point ), $this->proxy, $filter );
    }

    /**
     * Get request type
     *
     * Retreive request type
     *
     * @param  string $id Location parameters
     * @return object
     */
    public function getRequestType( $id )
    {
        return new RequestType($this->proxy->getRequestType( $id ), $this->proxy );
    }

    /**
     * Get issues this place
     *
     * @param  array $params Search params
     * @return object
     */
    public function getPlaces( array $params = null )
    {
        $params = (array)$params;
        $issues = new PlaceCollection( $this->proxy->searchPlaces( $params ), $this->proxy );
        return $issues;
    }

    /**
     * Get place
     *
     * Retreive a place given it's ID
     *
     * @param  int $id ID of the place to retrieve
     * @return \SeeClickFix\Place
     */
    public function getPlace( $id )
    {
        $place = new Place( $this->proxy->getPlace( $id ), $this->proxy );
        return $place;
    }

    /**
     * Get current user
     *
     * Returns the current user wrapped in a CurrentUser object
     *
     * @return \SeeClickFix\CurrentUser
     */
    public function getCurrentUser()
    {
        if (is_null($this->current_user))
        {
            $this->current_user = new CurrentUser( $this->proxy->getCurrentUser(), $this->proxy );
        }

        return $this->current_user;
    }
}
