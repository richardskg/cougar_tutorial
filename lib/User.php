<?php

namespace CougarTutorial;

use Cougar\Security\iSecurity;
use CougarTutorial\Security\ActionAuthorizationQuery;

/**
 * Manages application users.
 *
 * @package CougarTutorial
 */
class User implements iUser
{
    /**
     * Stores reference to the security context and model factory
     *
     * @param iSecurity $security
     *   Security context
     * @param ModelFactory $factory
     *   Model factory
     */
    public function __construct(iSecurity $security, ModelFactory $factory)
    {
        // Store the reference to the security context
        $this->security = $security;
        $this->factory = $factory;
    }


    /***************************************************************************
     * PUBLIC PROPERTIES AND METHODS
     **************************************************************************/

    /**
     * Greets the user
     *
     * @Path /greeting
     * @Methods GET
     * @Authentication optional
     * @XmlRootElement greeting
     *
     * @return string Greeting
     */
    public function greet()
    {
        if ($this->security->isAuthenticated())
        {
            $identity = $this->security->getIdentity();
            return "Welcome, " . $identity->givenName;
        }
        else
        {
            return "Welcome, visitor";
        }
    }

    /**
     * Creates a new user from a given object or assoc. array that resembles
     * a UserModel object.
     *
     * @Path /user
     * @Methods POST
     * @Accepts json
     * @Body user object
     * @Authentication optional
     * @XmlRootElement user
     *
     * @param mixed $user
     *   User object or associative array
     * @return UserModel User model object
     */
    public function createUser($user)
    {
        // Create a new PDO model from the user object and import the values;
        // we have to create an empty model first or the PdoModel object will
        // try to fetch it based on the email address and cause a 404.
        // We could catch the 404 and create on that.
        $new_user = $this->factory->UserPdo();
        $new_user->__import($user);

        // Authorize the request; if authorization fails, an exception will be
        // thrown and execution will stop
        $auth_query = new ActionAuthorizationQuery();
        $auth_query->type = "user";
        $auth_query->action = "create";
        $auth_query->object = $new_user;
        $this->security->authorize("action", $auth_query);

        // Save the record
        $new_user->save();

        // Return a detached user model
        return $this->factory->User($new_user);
    }

    /**
     * Gets the user identified by the given User ID
     *
     * @Path /user/:id
     * @Methods GET
     * @Authentication required
     * @XmlRootElement user
     *
     * @param string $id
     *   User ID
     * @return UserModel User model object
     */
    public function getUser($id)
    {
        // Get the user
        $user = $this->factory->UserPdo(array("id" => $id));

        // Authorize the request; if authorization fails, an exception will be
        // thrown and execution will stop
        $auth_query = new ActionAuthorizationQuery();
        $auth_query->type = "user";
        $auth_query->action = "read";
        $auth_query->object = $user;
        $this->security->authorize("action", $auth_query);

        // Return a detached user model
        return $this->factory->User($user);
    }

    /**
     * Updates the given user with the object provided. If the provided object
     * does not have the ID property set, it may pass it as a method parameter.
     *
     * @Path /user/:id
     * @Methods PUT
     * @Accepts json
     * @Body user object
     * @Authentication required
     * @XmlRootElement user
     *
     * @param mixed $user
     *   User object or associative array (must contain changes)
     * @param string $id
     *   User ID (optional)
     * @return UserModel Modified user model object
     */
    public function updateUser($user, $id = null)
    {
        // Add the ID to the user object
        if ($id)
        {
            if (is_array($user))
            {
                $user["id"] = $id;
            }
            else if (is_object($user))
            {
                $user->id = $id;
            }
        }

        // Get the user and apply changes
        $user = $this->factory->UserPdo($user);

        // Authorize the request; if authorization fails, an exception will be
        // thrown and execution will stop
        $auth_query = new ActionAuthorizationQuery();
        $auth_query->type = "user";
        $auth_query->action = "update";
        $auth_query->object = $user;
        $this->security->authorize("action", $auth_query);

        // Save the record
        $user->save();

        // Return a detached user model
        return $this->factory->User($user);
    }

    /**
     * Deletes the user with the given ID.
     *
     * @Path /user/:id
     * @Methods DELETE
     * @Authentication required
     * @XmlRootElement user
     *
     * @param string $id
     *   User ID
     */
    public function deleteUser($id)
    {
        // Get the user
        $user = $this->factory->UserPdo(array("id" => $id));

        // Authorize the request; if authorization fails, an exception will be
        // thrown and execution will stop
        $auth_query = new ActionAuthorizationQuery();
        $auth_query->type = "user";
        $auth_query->action = "delete";
        $auth_query->object = $user;
        $this->security->authorize("action", $auth_query);

        // Delete the user
        $user->delete();
    }

    
    /***************************************************************************
     * PROTECTED PROPERTIES AND METHODS
     **************************************************************************/

    /**
     * @var \Cougar\Security\iSecurity $security;
     */
    protected $security;

    /**
     * @var ModelFactory Model factory
     */
    protected $factory;
}
