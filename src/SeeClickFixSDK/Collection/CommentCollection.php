<?php namespace SeeClickFixSDK\Collection;

/**
 * Comment Collection
 *
 * Holds a collection of comments
 */
class CommentCollection extends \SeeClickFixSDK\Collection\CollectionAbstract {

    /**
     * Set the collection data
     *
     * @param StdClass $raw_data
     * @access public
     */
    public function setData( $data ) {
        $this->data = $data->comments;
        $this->convertData( '\SeeClickFixSDK\Comment' );
    }

    /**
     * Returns comments as an array
     *
     * @return array
     * @access public
     */
    public function toArray()
    {
        $data = array();

        foreach ($this->data as $comment) {
            $data[] = $comment->toArray();
        }

        return $data;
    }

}