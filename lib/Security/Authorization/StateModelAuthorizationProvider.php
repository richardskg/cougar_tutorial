<?php

namespace CougarTutorial\Security;

use Cougar\Security\iAuthorizationProvider;
use Cougar\Security\iIdentity;
use Cougar\Exceptions\Exception;

class StateModelAuthorizationProvider implements iAuthorizationProvider
{
    /**
     * @var string Defines the default provider alias
     */
    public $providerAlias = "state.model";

    /**
     * Sets up the State PDO Model security constraints for the given identity.
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
            // See if the identity is an administrator
            if ($identity->admin)
            {
                // Set the allowed actions
                $query->allowCreate = false;
                $query->allowRead = true;
                $query->allowUpdate = true;
                $query->allowDelete = false;
                $query->allowQuery = true;

                // The administrator is only able to update the population and
                // largest city; restrict all other fields
                $query->readOnly["stateId"] = true;
                $query->readOnly["name"] = true;
                $query->readOnly["capital"] = true;
                $query->readOnly["largestCity"] = false;
                $query->readOnly["unionDate"] = true;
                $query->readOnly["landArea"] = true;
                $query->readOnly["counties"] = true;
                $query->readOnly["population"] = false;
            }
            else
            {
                // Set the allowed actions
                $query->allowCreate = false;
                $query->allowRead = true;
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
