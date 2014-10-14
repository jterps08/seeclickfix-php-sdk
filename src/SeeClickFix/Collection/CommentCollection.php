<?php namespace SeeClickFix\Collection;

/**
 * Comment Collection
 *
 * Holds a collection of comments
 */
class CommentCollection extends \SeeClickFix\Collection\CollectionAbstract {

    /**
     * Set the collection data
     *
     * @param object $data
     */
    public function setData($data)
    {
        $this->data = $data->comments;
        $this->convertData( '\SeeClickFix\Comment' );
    }

    /**
     * Returns comments as an array
     *
     * @return array
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