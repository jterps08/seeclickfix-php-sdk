<?php namespace SeeClickFixSDK;

/**
 * Issue class
 *
 * @see \SeeClickFixSDK\SeeClickFixSDK->getPlace()
 */
class RequestType extends \SeeClickFixSDK\Core\BaseObjectAbstract {

    /**
     * Returns the request type as an array
     *
     * @return array
     * @access public
     */
    public function toArray()
    {
        return array(
            'id'            => $this->id,
            'title'         => $this->title,
            'organization'  => $this->organization,
            'url'           => $this->url
        );
    }

}
