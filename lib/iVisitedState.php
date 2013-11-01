<?php

namespace CougarTutorial;

/**
 * Manages visited states, allowing the declaration, deletion and querying of
 * states that have been visited by users.
 *
 * @package CougarTutorial
 */
interface iVisitedState
{
    /**
     * Sets the state as visited by the identity id or, if identity has admin
     * rights, the given userId
     *
     * @param string $state_id
     *   State abbreviation that was visited
     * @param string $user_id
     *   Optional userId for administrators
     */
    public function stateVisited($state_id, $user_id = null);

    /**
     * Declares that a state has not bee visited by the user
     *
     * @param string $state_id
     *   State abbreviation that was visited
     * @param string $user_id
     *   Optional userId for administrators
     */
    public function stateNotVisited($state_id, $user_id = null);

    /**
     * Gets the list of states that have been visited by the given user. If
     * userId is omitted, it will return the list of states visited by the
     * authenticated identity.
     *
     * @param string $user_id
     *   userId to use (optional)
     * @return array visited states
     */
    public function getVisitedStatesByUser($user_id = null);

    /**
     * Gets the list of users that have visited a particular state. If the
     * identity has been authenticated, user information will be retrieved.
     * Otherwise, only the visitor count will be retrieved.
     *
     * @param string $state_id
     *   State ID to get information from
     * @return mixed Users that have visited the state
     */
    public function getStateVisitors($state_id);
}
