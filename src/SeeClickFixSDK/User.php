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

    /**
     * Get errors
     *
     * @return string
     * @access public
     */
    public function errors()
    {
        // This is horrible, talk to devs about fixing the response
        $error = '';
        foreach($this->data as $key=>$value)
        {
            if(is_array($value))
            {
                $error .= $key.' ';
                foreach($value as $e) {
                    $error .= $e.' ';
                }
            }
            else {
                $error .= $value.' ';
            }
        }
        return $error;
    }

}