<?php

namespace CougarTutorial\Security;

use Cougar\Security\iAuthorizationProvider;
use Cougar\Security\iIdentity;
use Cougar\Exceptions\Exception;

class UserModelAuthorizationProvider implements iAuthorizationProvider
{
    /**
     * @var string Defines the default provider alias
     */
    public $providerAlias = "user.model";

    /**
     * Sets up the State PDO Model security constraints for the given identity.
     *
     * @param iIdentity $identity
     *   Identity object
     * @param \CougarTutorial\Security\ActionAuthorizationQuery $query
     *   User authorization query
     * @return bool True if authorized, throws exception otherwise
     * @throws \Cougar\Exceptions\Exception
     */
    public function authorize(iIdentity $identity, $query)
    {
        // Make sure the query is the right type
        if ($query instanceof ModelAuthorizationQuery)
        {
            // See if we have an ID
            if ($identity->id)
            {
                // See if the identity is an administrator
                if ($identity->admin)
                {
                    // Set the allowed actions
                    $query->allowCreate = true;
                    $query->allowRead = true;
                    $query->allowUpdate = true;
                    $query->allowDelete = true;
                    $query->allowQuery = true;

                    // The administrator can update anything about the user
                    $query->readOnly["givenName"] = false;
                    $query->readOnly["lastName"] = false;
                    $query->readOnly["emailAddress"] = false;
                    $query->readOnly["password"] = false;
                    $query->readOnly["admin"] = false;
                }
                else if ($identity->id == $query->object->id)
                {
                    // Set the allowed actions for the user on their own object
                    $query->allowCreate = false;
                    $query->allowRead = true;
                    $query->allowUpdate = true;
                    $query->allowDelete = true;
                    $query->allowQuery = false;

                    // Users can update their name and password; they certainly
                    // can't make themselves administrators
                    $query->readOnly["givenName"] = false;
                    $query->readOnly["lastName"] = false;
                    $query->readOnly["emailAddress"] = true;
                    $query->readOnly["password"] = false;
                    $query->readOnly["admin"] = true;

                    // Make sure the user doesn't see the admin field
                    $query->visible["admin"] = false;
                }
                else
                {
                    // Set the allowed actions for a user viewing another user
                    $query->allowCreate = false;
                    $query->allowRead = true;
                    $query->allowUpdate = false;
                    $query->allowDelete = false;
                    $query->allowQuery = false;

                    // Make sure the user doesn't see the admin field or
                    // password
                    $query->visible["password"] = false;
                    $query->visible["admin"] = false;
                }
            }
            else
            {
                // Unauthenticated users can only create a new user
                $query->allowCreate = true;
                $query->allowRead = false;
                $query->allowUpdate = false;
                $query->allowDelete = false;
                $query->allowQuery = false;
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
