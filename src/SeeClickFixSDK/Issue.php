<?php namespace SeeClickFixSDK;

use \SeeClickFixSDK\Comment;
use \SeeClickFixSDK\User;
use \SeeClickFixSDK\Location;
use \SeeClickFixSDK\Collection\CommentCollection;
use \SeeClickFixSDK\Collection\UserCollection;

/**
 * Issue class
 *
 * @see \SeeClickFixSDK\SeeClickFixSDK->getLocation()
 */
class Issue extends \SeeClickFixSDK\Core\BaseObjectAbstract {

    /**
     * User cache
     *
     * @var \SeeClickFixSDK\User
     */
    protected $user = null;

    /**
     * Comments cache
     *
     * @var \SeeClickFixSDK\Collection\CommentCollection
     */
    protected $comments = null;

    /**
     * Location cache
     *
     * @var \SeeClickFixSDK\Location
     */
    protected $location = null;

    /**
     * Get the ID
     *
     * @return string
     * @access public
     */
    public function getId() {
        return $this->data->id;
     }

    /**
     * Get the thumbnail
     *
     * @return string
     * @access public
     */
    public function getThumbnail($size = 'full') {
        if($size === 'square') {
            return $this->data->media->image_square_100x100;
        }
        return $this->data->media->image_full;
    }

    /**
     * Get the created time
     *
     * @param string $format {@link http://php.net/manual/en/function.date.php}
     * @return string
     * @access public
     */
    public function getCreatedTime( $format = null ) {
        if ( $format ) {
            $date = date( $format, $this->data->created_at );
        }
        else {
            $date = $this->data->created_at;
        }
        return $date;
    }

    /**
     * Get the reporter that posted the issue
     *
     * @return \SeeClickFixSDK\User
     * @access public
     */
    public function getReporter() {
        if ( !$this->user ) {
            $this->user = new User( $this->data->reporter, $this->proxy );
        }
        return $this->user;
    }

    /**
     * Get issue comments
     *
     * Return all the comments associated with a issue
     *
     * @return \SeeClickFixSDK\CommentCollection
     * @access public
     */
    public function getComments() {
        if ( $this->comments_count > 0 && !$this->comments) {
            $this->comments = new CommentCollection( $this->proxy->getIssueComments( $this->getId() ), $this->proxy );
        }
        return $this->comments;
    }

    /**
     * Get the issue's vote count
     *
     * @return int
     * @access public
     */
    public function getVoteCount() {
        return (int)$this->data->rating;
    }

    /**
     * Get location status
     *
     * Will return true if any location data is associated with the issue
     *
     * @return bool
     * @access public
     */
    public function hasLocation() {
        return isset( $this->data->lat ) && isset( $this->data->lng );
    }

    /**
     * Get the location
     *
     * Returns the location associated with the issue or null if no location data is available
     *
     * @param bool $force_fetch Don't use the cache
     * @return \SeeClickFixSDK\Location|null
     * @access public
     */
    public function getLocation( $force_fetch = false ) {
        if ( !$this->hasLocation() ) {
            return null;
        }
        if ( !$this->location || (bool)$force_fetch ) {
            $this->location = new Location( $this->data->location, isset( $this->data->location->id ) ? $this->proxy : null );
        }
        return $this->location;
    }

    /**
     * Magic toString method
     *
     * Returns the issue's thumbnail url
     *
     * @return string
     * @access public
     */
    public function __toString() {
        return $this->getThumbnail()->url;
    }

}