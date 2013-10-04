<?php namespace SeeClickFixSDK;

use \SeeClickFixSDK\Collection\MediaCollection;
use \SeeClickFixSDK\Collection\UserCollection;

/**
 * User class
 */
class User extends \SeeClickFixSDK\Core\BaseObjectAbstract {

    /**
     * Get the user's username
     *
     * @return string
     * @access public
     */
    public function getName() {
        return $this->data->name;
    }

    /**
     * Get the user's avatar
     *
     * @return string
     * @access public
     */
    public function getAvatar($size = 'full') {
        return $this->data->avatar->$size;
    }

    /**
     * Get the user's civic points
     *
     * @return string
     * @access public
     */
    public function getCivicPoints() {
        return $this->data->civic_points;
    }

    /**
     * Get the user's issues voted count
     *
     * @return string
     * @access public
     */
    public function getIssueVoteCount() {
        return $this->data->voted_issue_count;
    }

    /**
     * Get the user's reported issue count
     *
     * @return string
     * @access public
     */
    public function getReportIssueCount() {
        return $this->data->reported_issue_count;
    }

    /**
     * Get the user's comment count
     *
     * @return string
     * @access public
     */
    public function GetCommentsCount() {
        return $this->data->comments_count;
    }

    /**
     * Get the user's closed issue count
     *
     * @return string
     * @access public
     */
    public function getClosedIssueCount() {
        return $this->data->closed_issue_count;
    }

    /**
     * Get the user's following issue count
     *
     * @return string
     * @access public
     */
    public function getFollowingIssueCount() {
        return $this->data->following_issue_count;
    }

}