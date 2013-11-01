<?php

namespace CougarTutorial\Security;

use Cougar\Security\iAuthorizationProvider;
use Cougar\Security\iIdentity;
use Cougar\Exceptions\Exception;

class VisitedStateModelAuthorizationProvider implements iAuthorizationProvider
{
    /**
     * @var string Defines the default provider alias
     */
    public $providerAlias = "visitedstate.model";

    /**
     * Sets up the Visited State PDO Model security constraints for the given
     * identity.
     *
     * @param iIdentity $identity
     *   Identity object
     * @param \CougarTutorial\Security\ActionAuthorizationQuery $query
     *   User authorization query
     * @return bool True if authorized, throws exception otherwise
     * @throws \Cougar\Exceptions\AccessDeniedException
     */
    public function authorize(iIdentity $identity, $query)
    {
        // Make sure the query is the right type
        if ($query instanceof ModelAuthorizationQuery)
        {
            //
            if ($identity->id && ($identity->admin ||
                $identity->id == $query->object->userId))
            {
                // Set the allowed actions
                $query->allowCreate = true;
                $query->allowRead = true;
                // This is a many-to-many relationship table;
                // Updating doesn't really make sense
                $query->allowUpdate = false;
                $query->allowDelete = true;
                $query->allowQuery = true;
            }
            else if ($identity->id)
            {
                // Set the allowed actions
                $query->allowCreate = true;
                $query->allowRead = false;
                $query->allowUpdate = false;
                $query->allowDelete = true;
                $query->allowQuery = true;
            }
            else
            {
                // Set the allowed actions
                $query->allowCreate = false;
                $query->allowRead = false;
                $query->allowUpdate = false;
                $query->allowDelete = false;
                $query->allowQuery = true;
            }
        }
        else
        {
            // Bad query; throw an exception
            throw new Exception("Invalid authorization query");
        }
    }
}
?>
