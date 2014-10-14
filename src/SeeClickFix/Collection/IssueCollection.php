<?php namespace SeeClickFix\Collection;

/**
 * Issue Collection
 *
 * Holds a collection of iccues
 */
class IssueCollection extends \SeeClickFix\Collection\CollectionAbstract {

    /**
     * Set the collection data
     *
     * @param object $data
     */
    public function setData($data)
    {
        $this->data = $data->issues;

        $this->convertData('\SeeClickFix\Issue');
    }
}