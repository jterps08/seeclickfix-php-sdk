<?php namespace SeeClickFix\Collection;

/**
 * User Collection
 *
 * Holds a collection of users
 */
class UserCollection extends \SeeClickFix\Collection\CollectionAbstract {

    /**
     * Set the collection data
     *
     * @param object $raw_data
     */
    public function setData( $raw_data )
    {
        $this->data = $raw_data->users;
        $this->pagination = isset( $raw_data->pagination ) ? $raw_data->pagination : null;
        $this->convertData( '\SeeClickFix\User' );
    }

    /**
     * Get next max cursor
     *
     * Get the next max cursor for use in pagination
     *
     * @return string Returns the next max cursor
     */
    public function getNextCursor()
    {
        return isset( $this->pagination->next_cursor ) && !empty( $this->pagination->next_cursor ) ? $this->pagination->next_cursor : null;
    }

    /**
     * Get next max cursor
     *
     * Get the next max cursor for use in pagination
     *
     * @return string Returns the next max cursor
     */
    public function getNext()
    {
        return $this->getNextCursor();
    }

}
