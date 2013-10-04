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
     * Constructor
     *
     * You can supply a client, proxy, and an access token via the config array
     *
     * @param string $access_token SeeClickFix access token obtained through authentication
     * @param \SeeClickFixSDK\Net\ClientInterface $client Client object used to connect to the API
     * @access public
     */
    public function __construct( $access_token = null, \SeeClickFixSDK\Net\ClientInterface $client = null ) {
        $this->proxy = new \SeeClickFixSDK\Core\Proxy( $client ?: new \SeeClickFixSDK\Net\CurlClient, $access_token ?: null );
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