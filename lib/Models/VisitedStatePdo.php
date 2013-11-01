<?php

namespace CougarTutorial\Models;

use Cougar\Model\iStoredModel;
use Cougar\Model\tPdoModel;
use Cougar\Security\iSecurity;
use Cougar\Exceptions\BadRequestException;
use CougarTutorial\Security\ModelAuthorizationQuery;

/**
 * Defines the VisitedState PDO model.
 *
 * @Table VisitedState
 * @Allow READ QUERY
 * @PrimaryKey userId stateId
 */
class VisitedStatePdo extends VisitedStateBase implements iStoredModel
{
	use tPdoModel;

    /**
     * Sets up the model security based on the current identity
     *
     * @param \Cougar\Security\iSecurity $security
     *   Security context
     * @param bool $create
     *   Whether to allow CREATE operation
     * @param bool $read
     *   Whether to allow READ operation
     * @param bool $update
     *   Whether to allow UPDATE operation
     * @param bool $delete
     *   Whether to allow QUERY operation
     * @param $query
     * @param array $columns
     *   Columns accessed by this object
     * @param array $readOnlyPropertyAttributes
     *   Whether the properties are ready-only
     * @param array $propertyVisibility
     *   Whether the properties are visible
     */
    protected function authorization(iSecurity $security, &$create, &$read,
        &$update, &$delete, &$query, array $columns,
        array &$readOnlyPropertyAttributes, array &$propertyVisibility)
    {
        // Create the authorization query
        $auth_query = new ModelAuthorizationQuery();
        $auth_query->allowCreate = &$create;
        $auth_query->allowRead = &$read;
        $auth_query->allowUpdate = &$update;
        $auth_query->allowDelete = &$delete;
        $auth_query->allowQuery = &$query;
        $auth_query->columnMap = $columns;
        $auth_query->readOnly = &$readOnlyPropertyAttributes;
        $auth_query->visible = &$propertyVisibility;
        $auth_query->object = $this;

        // Make the authorization query
        $this->__security->authorize("visitedstate.model", $auth_query);
    }

    /**
     * Gets a list of users that have visited the given state.
     *
     * @param string $state_id
     * @return array List of users that have visited the given state
     * @throws AccessDeniedException
     */
    public function queryVisitors($state_id)
    {
        // See if querying is allowed
        if (! $this->__allowQuery)
        {
            throw new AccessDeniedException(
                "This model does not support querying");
        }

        // Create the cache key see if we have the result stored in the cache
        if ($this->__noQueryCache)
        {
            $results = false;
        }
        else
        {
            // Since the query is different for whether the identity has been
            // authenticated, we must use two different keys
            if ($this->__security->isAuthenticated())
            {
                $cache_key = $this->__cachePrefix . ".query.visitors." .
                    $state_id;
            }
            else
            {
                $cache_key = $this->__cachePrefix . ".query.public.visitors." .
                    $state_id;
            }
            $results = $this->__cache->get($cache_key);
        }

        if ($results === false)
        {
            // Build the proper query
            if ($this->__security->isAuthenticated())
            {
                $sql_statement = "SELECT GivenName as givenName, " .
                        "LastName as lastName, Email as emailAddress, " .
                        "State.Name as state " .
                    "FROM VisitedState " .
                    "JOIN User USING (Email) " .
                    "JOIN State USING (StateID) " .
                    "WHERE stateId = :state_id " .
                    "ORDER BY givenName, lastName, emailAddress";
            }
            else
            {
                $sql_statement = "SELECT State.Name as state " .
                    "FROM VisitedState " .
                    "JOIN State USING (StateID) " .
                    "WHERE stateId = :state_id ";
            }

            // Prepare and execute the statement
            $statement = $this->__pdo->prepare($sql_statement);
            $statement->execute(array("state_id" => $state_id));

            // Get the results
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

            // Store the results in the cache
            if (! $this->__noQueryCache)
            {
                $this->__cache->set($cache_key, $results, $this->__cacheTime);
            }
        }

        // Return the result
        return $results;
    }

    /**
     * Gets the count of users that have visited a particular state. This is an
     * optimized version of the queryVisitors() method because aggregation is
     * performed on the database.
     *
     * @param string $state_id
     * @return int Number of visitors to the state
     * @throws AccessDeniedException
     */
    public function queryVisitorCount($state_id)
    {
        // See if querying is allowed
        if (! $this->__allowQuery)
        {
            throw new AccessDeniedException(
                "This model does not support querying");
        }

        // Create the cache key see if we have the result stored in the cache
        if ($this->__noQueryCache)
        {
            $count = false;
        }
        else
        {
            // Figure out the cache key
            $cache_key = $this->__cachePrefix . ".query.visitors.count" .
                $state_id;
            $count = $this->__cache->get($cache_key);
        }

        if ($count === false)
        {
            // Build the query
            $sql_statement = "SELECT COUNT(*) AS VisitorCount " .
                "FROM VisitedState " .
                "WHERE stateId = :state_id ";

            // Prepare and execute the statement
            $statement = $this->__pdo->prepare($sql_statement);
            $statement->execute(array("state_id" => $state_id));

            // Get the results
            $count = $statement->fetchColumn();

            // Store the results in the cache
            if (! $this->__noQueryCache)
            {
                $this->__cache->set($cache_key, $count, $this->__cacheTime);
            }
        }

        // Return the result
        return $count;
    }

    /**
     * Gets the list of states visited by the given user.
     *
     * @param string $user_id
     * @return array List states visited by a user
     * @throws AccessDeniedException
     */
    public function queryVisited($user_id)
    {
        // See if querying is allowed
        if (! $this->__allowQuery)
        {
            throw new AccessDeniedException(
                "This model does not support querying");
        }

        // Create the cache key see if we have the result stored in the cache
        if ($this->__noQueryCache)
        {
            $results = false;
        }
        else
        {
            // Since the query is different for whether the identity has been
            // authenticated, we must use two different keys
            if ($this->__security->isAuthenticated())
            {
                $cache_key = $this->__cachePrefix . ".query.visited." .
                    $user_id;
            }
            else
            {
                $cache_key = $this->__cachePrefix . ".query.public.visited." .
                    $user_id;
            }
            $results = $this->__cache->get($cache_key);
        }

        if ($results === false)
        {
            // Build the proper query
            if ($this->__security->isAuthenticated())
            {
                $sql_statement = "SELECT GivenName as givenName, " .
                    "LastName as lastName, Email as emailAddress, " .
                    "State.Name as state " .
                    "FROM VisitedState " .
                    "JOIN User USING (Email) " .
                    "JOIN State USING (StateID) " .
                    "WHERE Email = :user_id " .
                    "ORDER BY Name";
            }
            else
            {
                $sql_statement = "SELECT State.Name as state " .
                    "FROM VisitedState " .
                    "JOIN State USING (StateID) " .
                    "WHERE Email = :user_id " .
                    "ORDER BY Name";
            }

            // Prepare and execute the statement
            $statement = $this->__pdo->prepare($sql_statement);
            $statement->execute(array("user_id" => $user_id));

            // Get the results
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

            // Store the results in the cache
            if (! $this->__noQueryCache)
            {
                $this->__cache->set($cache_key, $results, $this->__cacheTime);
            }
        }

        // Return the result
        return $results;
    }
}
?>
