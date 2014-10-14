<?php namespace SeeClickFix;

use \SeeClickFix\Collection\IssueCollection;

/**
 * Place class
 *
 * Some media has a place associated to it. This place will have an ID and a name.
 * Some media has no place associated, but has a lat/lng. These place objects will return null or '' for certain method calls
 */
class Place extends \SeeClickFix\Core\BaseObjectAbstract {

    /**
     * Comments cache
     *
     * @var \SeeClickFix\Collection\IssueCollection
     */
    protected $issues = null;

    /**
     * Get place slug
     *
     * @return string|null
     */
    public function getSlug()
    {
        return isset( $this->data->url_name ) ? $this->data->url_name : null;
    }

    /**
     * Get place type (city, neighborhood...)
     *
     * @return string|null
     */
    public function getType()
    {
        return isset( $this->data->data_classification ) ? $this->data->data_classification : null;
    }
}
