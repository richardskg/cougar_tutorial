<?php

namespace CougarTutorial;

/**
 * Defines the User interface which manages application users
 *
 * @package CougarTutorial
 */
interface iUser
{
    /**
     * Greets the user; if the user is not authenticated it should still provide
     * a useful greeting
     *
     * @return string Greeting
     */
    public function greet();

    /**
     * Creates a new user from a given object or assoc. array that resembles
     * a UserModel object.
     *
     * @param mixed $user
     *   User object or associative array
     * @return UserModel User model object
     */
    public function createUser($user);

    /**
     * Gets the user identified by the given User ID
     *
     * @param string $id
     *   User ID
     * @return UserModel User model object
     */
    public function getUser($id);

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
    public function updateUser($user, $id = null);

    /**
     * Deletes the user with the given ID.
     *
     * @param string $id
     *   User ID
     */
    public function deleteUser($id);
}