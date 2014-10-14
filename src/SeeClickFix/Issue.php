<?php namespace SeeClickFix;

use \SeeClickFix\Comment;
use \SeeClickFix\User;
use \SeeClickFix\Place;
use \SeeClickFix\Collection\CommentCollection;
use \SeeClickFix\Collection\UserCollection;

/**
 * Issue class
 *
 * @see \SeeClickFix\SeeClickFix->getPlace()
 */
class Issue extends \SeeClickFix\Core\BaseObjectAbstract {

    /**
     * User cache
     *
     * @var \SeeClickFix\User
     */
    protected $user = null;

    /**
     * Comments cache
     *
     * @var \SeeClickFix\Collection\CommentCollection
     */
    protected $comments = null;

    /**
     * Place cache
     *
     * @var \SeeClickFix\Place
     */
    protected $place = null;

    /**
     * Constructor
     *
     * @param object $data Object's data
     * @param \SeeClickFix\Core\Proxy $proxy Object's proxy
     */
    public function __construct($data, \SeeClickFix\Core\Proxy $proxy = null)
    {
        // Normalize status
        if (isset($data->status)) {
            $data->status = strtolower($data->status);
        }

        parent::__construct($data, $proxy);
    }

    /**
     * Get the ID
     *
     * @return string
     */
    public function getId()
    {
        return isset($this->data->id) ? $this->data->id : null;
    }

    /**
     * Get the thumbnail
     *
     * @return string
     */
    public function getThumbnail($size = 'full')
    {
        if( !isset($this->data->media) ) {
            return '';
        }

        if($size === 'square') {
            $image = $this->data->media->image_square_100x100;
        }
        else {
            $image = $this->data->media->image_full;
        }

        // TODO: API BUG. Bug reported (4292)
        if($image && strpos($image, 'http') !== 0) {
            $image = 'http://%sseeclickfix.com' . $image;
            $image = sprintf( $image, (\Config::get('laravel-seeclickfix-api::sandbox_mode') ? 'test.' : '') );
        }

        return $image;
    }

    /**
     * Get the created time
     *
     * @param  string $format {@link http://php.net/manual/en/function.date.php}
     * @return string
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
     * @return \SeeClickFix\User
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
     * @return \SeeClickFix\CommentCollection
     */
    public function getComments()
    {
        // Already loaded?
        if($this->comments)
            return $this->comments;

        if ( $this->comments_count > 0 && ! $this->comments) {
            return $this->comments = new CommentCollection( $this->proxy->getIssueComments( $this->getId() ), $this->proxy );
        }
        else {
            return $this->comments = new CommentCollection( null, $this->proxy );
        }
    }

    /**
     * Get the issue's vote count
     *
     * @return int
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
     * @param  bool $force_fetch Don't use the cache
     * @return \SeeClickFix\Place|null
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
     */
    public function __toString()
    {
        return $this->getThumbnail()->url;
    }

    /**
     * Returns the issue as an array
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            "id"            => $this->getId(),
            "status"        => strtolower($this->status),
            "summary"       => $this->summary,
            "description"   => $this->description,
            "rating"        => $this->rating,
            "lat"           => $this->lat,
            "lng"           => $this->lng,
            "address"       => $this->address,
            "thumbnail"     => $this->getThumbnail(),
            "created_at"    => $this->created_at,
            "updated_at"    => $this->updated_at
        );
    }
}