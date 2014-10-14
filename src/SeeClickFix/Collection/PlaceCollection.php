<?php namespace SeeClickFix\Collection;

/**
 * Comment Collection
 *
 * Holds a collection of comments
 */
class PlaceCollection extends \SeeClickFix\Collection\CollectionAbstract {

    /**
     * Set the collection data
     *
     * @param object $data
     */
    public function setData($data)
    {
        $this->data = $data->places;

        $this->convertData( '\SeeClickFix\Place' );
    }
}