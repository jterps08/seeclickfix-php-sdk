<?php namespace SeeClickFixSDK;

use \SeeClickFixSDK\Comment;
use \SeeClickFixSDK\User;
use \SeeClickFixSDK\Place;
use \SeeClickFixSDK\Collection\CommentCollection;
use \SeeClickFixSDK\Collection\UserCollection;

/**
 * Issue class
 *
 * @see \SeeClickFixSDK\SeeClickFixSDK->getPlace()
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
     * Place cache
     *
     * @var \SeeClickFixSDK\Place
     */
    protected $place = null;

    /**
     * Get the ID
     *
     * @return string
     * @access public
     */
    public function getId()
    {
        return $this->data->id;
     }

    /**
     * Get the thumbnail
     *
     * @return string
     * @access public
     */
    public function getThumbnail($size = 'full')
    {
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
    public function getCreatedTime( $format = null )
    {
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
    public function getReporter()
    {
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
    public function getComments()
    {
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
    public function getVoteCount()
    {
        return (int)$this->data->rating;
    }

    /**
     * Get place status
     *
     * Will return true if any place data is associated with the issue
     *
     * @return bool
     * @access public
     */
    public function hasPlace()
    {
        return isset( $this->data->lat ) && isset( $this->data->lng );
    }

    /**
     * Get the place
     *
     * Returns the place associated with the issue or null if no place data is available
     *
     * @param bool $force_fetch Don't use the cache
     * @return \SeeClickFixSDK\Place|null
     * @access public
     */
    public function getPlace( $force_fetch = false )
    {
        if ( !$this->hasPlace() ) {
            return null;
        }
        if ( !$this->place || (bool)$force_fetch ) {
            $this->place = new Place( $this->data->location, isset( $this->data->location->id ) ? $this->proxy : null );
        }
        return $this->place;
    }

    /**
     * Magic toString method
     *
     * Returns the issue's thumbnail url
     *
     * @return string
     * @access public
     */
    public function __toString()
    {
        return $this->getThumbnail()->url;
    }

}