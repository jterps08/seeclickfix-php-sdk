<?php namespace SeeClickFixSDK;

/**
 * Current User
 *
 * Holds the currently logged in user
 *
 * @see \SeeClickFixSDK\SeeClickFixSDK->getCurrentUser()
 */
class CurrentUser extends \SeeClickFixSDK\User {

    /**
     * Holds voted info for the current user
     *
     * Current user votes are stored in issue objects
     * If an issue is voted after an issue has been fetched the like will not be a part of the issue object
     *
     * @access protected
     * @var array
     */
    protected $voted = array();

    /**
     * Add vote from current user
     *
     * @param \SeeClickFixSDK\Issues|string $issues Issues to add a like to from the current user
     * @access public
     */
    public function addIssueVote( $issue ) {
        if ( $issue instanceof \SeeClickFixSDK\Issues ) {
            $issue = $issue->getId();
        }
        if($this->proxy->addIssueVote( $issue )) {
            $this->voted[$issue] = true;
            return true;
        }
        return false;
    }

    /**
     * Current user follow the issue
     *
     * @param \SeeClickFixSDK\Issues|string $issues Issues to add a like to from the current user
     * @access public
     */
    public function followIssue( $issue ) {
        if ( $issue instanceof \SeeClickFixSDK\Issues ) {
            $issue = $issue->getId();
        }

        return ($this->proxy->followIssue( $issue ) ? true : false);
    }

    /**
     * Add a comment
     *
     * @param \SeeClickFixSDK\Issues|string Issues to add a comment to
     * @param string $text Comment text
     * @param array $params Optional parameters
     * @access public
     */
    public function addIssueComment( $issue, $text, array $params = null ) {
        if ( $issue instanceof \SeeClickFixSDK\Issues ) {
            $issue = $issue->getId();
        }
        $this->proxy->addIssueComment( $issue, $text, $params );
    }

    /**
     * Add a comment
     *
     * @param \SeeClickFixSDK\Issues|string Issues to add a comment to
     * @param string $text Comment text
     * @access public
     */
    public function addIssueFlag( $issue, $text ) {
        if ( $issue instanceof \SeeClickFixSDK\Issues ) {
            $issue = $issue->getId();
        }
        $this->proxy->addContentFlag( $issue, $text, 'issues' );
    }

    /**
     * Add a new issue
     *
     * @param array $params Required parameters
     * @access public
     */
    public function createIssue( array $params ) {
        return $this->proxy->createIssue( $params );
    }
}