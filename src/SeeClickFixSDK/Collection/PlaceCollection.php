<?php namespace SeeClickFixSDK\Collection;

/**
 * Comment Collection
 *
 * Holds a collection of comments
 */
class PlaceCollection extends \SeeClickFixSDK\Collection\CollectionAbstract {

    /**
     * Set the collection data
     *
     * @param StdClass $raw_data
     * @access public
     */
    public function setData( $raw_data ) {
        $this->data = $raw_data->places;
        $this->convertData( '\SeeClickFixSDK\Place' );
    }

}