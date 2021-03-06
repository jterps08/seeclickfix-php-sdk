<?php namespace SeeClickFix;

/**
 * Current User
 *
 * Holds the currently logged in user
 *
 * @see \SeeClickFix\SeeClickFix->getCurrentUser()
 */
class CurrentUser extends \SeeClickFix\User {

    /**
     * Holds voted info for the current user
     *
     * Current user votes are stored in issue objects
     * If an issue is voted after an issue has been fetched the like will not be a part of the issue object
     *
     * @var array
     */
    protected $voted = array();

    /**
     * Update current user
     *
     * @param array $params Required parameters
     * @return \SeeClickFix\CurrentUser
     */
    public function update( array $params )
    {
        $response = $this->proxy->updateCurrentUser( $params );

        $this->setData( $response );

        return $this;
    }

    /**
     * Add vote from current user
     *
     * @param  \SeeClickFix\Issues|string $issues Issues to add a like to from the current user
     * @return bool
     */
    public function addIssueVote( $issue )
    {
        if ( $issue instanceof \SeeClickFix\Issues ) {
            $issue = $issue->getId();
        }

        if($this->proxy->addIssueVote( $issue ))
        {
            $this->voted[$issue] = true;
            return true;
        }

        return false;
    }

    /**
     * Current user follow the issue
     *
     * @param  \SeeClickFix\Issues|string $issues Issues to add a like to from the current user
     * @return bool
     */
    public function followIssue( $issue )
    {
        if ( $issue instanceof \SeeClickFix\Issues ) {
            $issue = $issue->getId();
        }

        return ($this->proxy->followIssue( $issue ) ? true : false);
    }

    /**
     * Add a comment
     *
     * @param  \SeeClickFix\Issues|string  Issues to add a comment to
     * @param  string $text   Comment text
     * @param  array  $params Optional parameters
     * @return Comment
     */
    public function addIssueComment( $issue, $text, array $params = null )
    {
        if ( $issue instanceof \SeeClickFix\Issues ) {
            $issue = $issue->getId();
        }

        return new Comment( $this->proxy->addIssueComment( $issue, $text, $params ), $this->proxy );
    }

    /**
     * Add a comment
     *
     * @param \SeeClickFix\Issues|string Issues to add a comment to
     * @param string $text Comment text
     */
    public function addIssueFlag( $issue, $text )
    {
        if ( $issue instanceof \SeeClickFix\Issues ) {
            $issue = $issue->getId();
        }
        $this->proxy->addContentFlag( $issue, $text, 'issues' );
    }

    /**
     * Add a new issue
     *
     * @param array $params Required parameters
     * @return \SeeClickFix\Issue
     */
    public function createIssue( array $params )
    {
        $response = $this->proxy->createIssue( $params );

        if ( ! isset($response->id) )
        {
            // TODO: This is shit guys, work on fixing this stuff in the API...I mean come on!!!
            $error = '';
            foreach($response as $key=>$value) {
                if(is_array($value)) {
                    $error .= $key.' ';
                    foreach($value as $e) {
                        $error .= ' '.$e;
                    }
                }
                else {
                    $error .= $value.' ';
                }
            }
            return (object) array('errors' => $error);
        }

        return new Issue( $response, $this->proxy );
    }
}
