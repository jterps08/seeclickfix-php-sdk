<?php namespace SeeClickFixSDK;

use \SeeClickFixSDK\User;

/**
 * Comment class
 */
class Comment extends \SeeClickFixSDK\Core\BaseObjectAbstract {

    /**
     * Cached user
     *
     * @var \SeeClickFixSDK\User
     */
    protected $user = null;

    /**
     * Get comment creation time
     *
     * @param $format Time format {@link http://php.net/manual/en/function.date.php}
     * @return string Returns the creation time with optional formatting
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
     * Get the comment text
     *
     * @access public
     * @return string
     */
    public function getComment() {
        return $this->data->comment;
    }

    /**
     * Get the comment's user
     *
     * @access public
     * @return \SeeClickFixSDK\User
     */
    public function getUser() {
        if ( !$this->user ) {
            $this->user = new User( $this->data->commenter, $this->proxy );
        }
        return $this->user;
    }

    /**
     * Get the thumbnail
     *
     * @return string
     * @access public
     */
    public function getThumbnail($size = 'full')
    {
        // Is there even an image?
        if( ! isset($this->data->media)) {
            return null;
        }

        if($size === 'square') {
            return $this->data->media->image_square_100x100;
        }
        return $this->data->media->image_full;
    }

    /**
     * Magic toString method
     *
     * Return the comment text
     *
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->getComment();
    }

    /**
     * Returns the comment as an array
     *
     * @return array
     * @access public
     */
    public function toArray() {
        return array(
            // "id"          => null, // How stupid there isn't an ID!
            "comment"     => $this->getComment(),
            "thumbnail"   => $this->getThumbnail('square'),
            "commenter"   => $this->getUser()->toArray(),
        );
    }
}