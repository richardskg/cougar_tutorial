<?php

namespace CougarTutorial;

/**
 * Gets information about U.S. states.
 *
 * @package CougarTutorial
 */
interface iState
{
    /**
     * Gets the list of U.S. States matching the given query criteria. If the
     * query list is empty, it will return all states.
     *
     * @param array $query
     *   List of query parameters
     * @return array List of states that match criteria
     */
    public function getStateList(array $query = array());

    /**
     * Gets the specified U.S. State
     *
     * @param string $id
     * @return StateModel State
     */
    public function getState($id);
}
