<?php namespace SeeClickFixSDK;

/**
 * Location class
 *
 * Some media has a location associated to it. This location will have an ID and a name.
 * Some media has no location associated, but has a lat/lng. These location objects will return null or '' for certain method calls
 */
class Location extends \SeeClickFixSDK\Core\BaseObjectAbstract {

    /**
     * Get location slug
     *
     * @return string|null
     * @access public
     */
    public function getSlug() {
        return isset( $this->data->url_name ) ? $this->data->url_name : null;
    }

    /**
     * Get location type (city, neighborhood...)
     *
     * @return string|null
     * @access public
     */
    public function getType() {
        return isset( $this->data->data_classification ) ? $this->data->data_classification : null;
    }

}