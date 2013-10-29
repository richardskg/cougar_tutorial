<?php

namespace CougarTutorial\Models;

use Cougar\Model\iStoredModel;
use Cougar\Model\tPdoModel;
use Cougar\Security\iSecurity;
use Cougar\Exceptions\BadRequestException;
use CougarTutorial\Security\ModelAuthorizationQuery;

/**
 * Defines the State PDO model.
 *
 * @Table State
 * @Allow READ QUERY
 * @PrimaryKey stateId
 * @QueryView list
 * @CacheTime 3600
 */
class StatePdo extends StateBase implements iStoredModel
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

        // Make the authorization query
        $this->__security->authorize("state.model", $auth_query);
    }

    /**
     * Validates the values that have changed
     */
    protected function __preValidate()
    {
        // The population can only go up, not down
        if ($this->population < $this->__defaultValues["population"])
        {
            throw new BadRequestException(
                "State population can only increase, not decrease");
        }
    }
}
?>
