<?php

namespace CougarTutorial\Security;

use CougarTutorial\Models\StateBase;
use CougarTutorial\Models\UserBase;
use Cougar\Security\iAuthorizationProvider;
use Cougar\Security\iIdentity;
use Cougar\Exceptions\AccessDeniedException;

class ActionAuthorizationProvider implements iAuthorizationProvider
{
    /***************************************************************************
     * PUBLIC PROPERTIES AND METHODS
     **************************************************************************/

    /**
     * @var string Defines the default provider alias
     */
    public $providerAlias = "action";

    /**
     * Makes sure the identity has access to the given resource for the given
     * action. The reference to the resource and the action are provided as an
     * ActionAuthorizationQuery.
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
        if ($query instanceof ActionAuthorizationQuery)
        {
            // See what the object is and make the proper authorization call
            switch($query->type)
            {
                case "state":
                    return $this->authorizeState($identity, $query->action,
                        $query->object);
                    break;
                case "user":
                    return $this->authorizeUser($identity, $query->action,
                        $query->object);
                    break;
                case "visited":
                    break;
            }
        }

        // Bad query; throw an exception
        throw new AccessDeniedException(
            "You do not have access to this resource");
    }


    /***************************************************************************
     * PROTECTED PROPERTIES AND METHODS
     **************************************************************************/

    /**
     * Makes sure the given identity has permission to perform the given action
     * on a State object
     *
     * @param \Cougar\Security\iIdentity $identity
     *   Identity object
     * @param string $action
     *   Action to perform on object
     * @param \CougarTutorial\Models\StateBase $state
     *   State object
     * @return bool True if identity has access; throws exception otherwise
     * @throws \Cougar\Exceptions\AccessDeniedException
     */
    protected function authorizeState(iIdentity $identity, $action,
        StateBase $state = null)
    {
        // See what the action is
        switch($action)
        {
            case "read":
            case "query":
                // Anyone can read and query
                return true;
                break;
            case "update":
                // Only an administrator can update a state
                if ($identity->admin)
                {
                    return true;
                }
                break;
            case "create":
            case "delete":
                // Nobody can create nor delete a state
                break;
        }

        // Reject anything else
        throw new AccessDeniedException(
            "You do not have access to this resource");
    }

    /**
     * Makes sure the given identity has permission to perform the given action
     * on a User object
     *
     * @param \Cougar\Security\iIdentity $identity
     *   Identity object
     * @param string $action
     *   Action to perform on object
     * @param \CougarTutorial\Models\UserBase $user
     *   User object
     * @return bool True if identity has access; throws exception otherwise
     * @throws \Cougar\Exceptions\AccessDeniedException
     */
    protected function authorizeUser(iIdentity $identity, $action,
        UserBase $user = null)
    {
        // See what the action is
        switch($action)
        {
            case "read":
            case "delete":
            case "update":
                // Users own their own objects; admins can manipulate any user
                if ($identity->admin)
                {
                    return true;
                }
                else if ($identity->id && $identity->id == $user->id)
                {
                    return true;
                }
                break;
            case "create":
                // Only non-authenticated users (visitors) or admins can create
                if ($identity->admin)
                {
                    return true;
                }
                else if ($identity->id === null)
                {
                    return true;
                }
                break;
            case "query":
                // Administrators can query the user list
                if ($identity->admin)
                {
                    return true;
                }
        }

        // Reject anything else
        throw new AccessDeniedException(
            "You do not have access to this resource");
    }
}
?>
