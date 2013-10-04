<?php namespace SeeClickFixSDK;

use \SeeClickFixSDK\Collection\MediaCollection;

/**
 * Location class
 *
 * Some media has a location associated to it. This location will have an ID and a name.
 * Some media has no location associated, but has a lat/lng. These location objects will return null or '' for certain method calls
 */
class Location extends \SeeClickFixSDK\Core\BaseObjectAbstract {

    /**
     * Get location ID
     *
     * @return string|null
     * @access public
     */
    public function getId() {
        return isset( $this->data->id ) ? $this->data->id : null;
    }

    /**
     * Get location name
     *
     * @return string|null
     * @access public
     */
    public function getName() {
        return isset( $this->data->name ) ? $this->data->name : null;
    }

    /**
     * Get location longitude
     *
     * Get the longitude of the location
     *
     * @return string|null
     * @access public
     */
    public function getLat() {
        return isset( $this->data->latitude ) && is_float( $this->data->latitude ) ? $this->data->latitude : null;
    }

    /**
     * Get location latitude
     *
     * Get the latitude of the location
     *
     * @return string|null
     * @access public
     */
    public function getLng() {
        return isset( $this->data->longitude ) && is_float( $this->data->longitude ) ? $this->data->longitude : null;
    }

}