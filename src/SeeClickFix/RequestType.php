<?php namespace SeeClickFix;

/**
 * Issue class
 *
 * @see \SeeClickFix\SeeClickFix->getPlace()
 */
class RequestType extends \SeeClickFix\Core\BaseObjectAbstract {

    /**
     * Returns the request type as an array
     *
     * @return array
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
