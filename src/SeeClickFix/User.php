<?php namespace SeeClickFix;

/**
 * User class
 */
class User extends \SeeClickFix\Core\BaseObjectAbstract {

    /**
     * Get the user's avatar
     *
     * @return string
     */
    public function getAvatar($size = 'full') {
        return $this->data->avatar->$size;
    }

    /**
     * Get errors
     *
     * @return string
     */
    public function errors()
    {
        // This is horrible, talk to devs about fixing the response
        $error = '';
        foreach($this->data as $key=>$value)
        {
            if(is_array($value))
            {
                $error .= strpos($key, '.') ? '' : $key.' ';
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

    /**
     * Returns the user as an array
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            "id"            => $this->getId(),
            "name"          => $this->name,
            "civic_points"  => $this->civic_points,
            "avatar"        => $this->getAvatar('square_100x100')
        );
    }
}