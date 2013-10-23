<?php

namespace CougarTutorial;

use Cougar\Security\iSecurity;

/**
 * Manages application users.
 *
 * @package CougarTutorial
 */
class User implements iUser
{
   /**
    * Stores reference to the security context
    *
    * @param iSecurity $security
    *   Security context
    */
    public function __construct(iSecurity $security)
    {
        // Store the reference to the security context
        $this->security = $security;
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
     * @param mixed $user
     *   User object or associative array
     * @return UserModel User model object
     */
    public function createUser($user)
    {
        throw new \Cougar\Exceptions\NotImplementedException("Not implemented");
    }

    /**
     * Gets the user identified by the given User ID
     *
     * @param string $id
     *   User ID
     * @return UserModel User model object
     */
    public function getUser($id)
    {
        throw new \Cougar\Exceptions\NotImplementedException("Not implemented");
    }

    /**
     * Updates the given user with the object provided. If the provided object
     * does not have the ID property set, it may pass it as a method parameter.
     *
     * @param mixed $user
     *   User object or associative array (must contain changes)
     * @param string $id
     *   User ID (optional)
     * @return UserModel Modified user model object
     */
    public function updateUser($user, $id = null)
    {
        throw new \Cougar\Exceptions\NotImplementedException("Not implemented");
    }

    /**
     * Deletes the user with the given ID.
     *
     * @param string $id
     *   User ID
     */
    public function deleteUser($id)
    {
        throw new \Cougar\Exceptions\NotImplementedException("Not implemented");
    }

    
    /***************************************************************************
     * PROTECTED PROPERTIES AND METHODS
     **************************************************************************/

    /**
     * @var \Cougar\Security\iSecurity $security;
     */
    protected $security;
}
