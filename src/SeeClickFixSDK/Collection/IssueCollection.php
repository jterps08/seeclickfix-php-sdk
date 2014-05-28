<?php namespace SeeClickFixSDK\Collection;

/**
 * Issue Collection
 *
 * Holds a collection of iccues
 */
class IssueCollection extends \SeeClickFixSDK\Collection\CollectionAbstract {

    /**
     * Set the collection data
     *
     * @param StdClass $raw_data
     * @access public
     */
    public function setData( $data ) {
        $this->data = $data->issues;
        $this->convertData( '\SeeClickFixSDK\Issue' );
    }

}