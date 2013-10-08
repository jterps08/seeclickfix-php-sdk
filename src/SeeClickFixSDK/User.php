<?php namespace SeeClickFixSDK;

/**
 * User class
 */
class User extends \SeeClickFixSDK\Core\BaseObjectAbstract {

    /**
     * Get the user's avatar
     *
     * @return string
     * @access public
     */
    public function getAvatar($size = 'full') {
        return $this->data->avatar->$size;
    }

}