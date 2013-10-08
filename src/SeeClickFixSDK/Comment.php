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
     * Magic toString method
     *
     * Return the comment text
     *
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->getComment();
    }

}