<?php

namespace CougarTutorial\Models;

use CougarTutorial\Security\iIdentityProvider;
use CougarTutorial\Security\Credentials;
use CougarTutorial\Security\UsernamePasswordCredentials;
use CougarTutorial\Security\ModelAuthorizationQuery;
use Cougar\Model\iStoredModel;
use Cougar\Model\tPdoModel;
use Cougar\Security\iSecurity;
use Cougar\Util\QueryParameter;
use Cougar\Exceptions\AccessDeniedException;

/**
 * Defines the User PDO model.
 *
 * @Table User
 * @Allow CREATE READ UPDATE DELETE QUERY
 * @PrimaryKey emailAddress
 * @CacheTime 3600
 */
class UserPdo extends UserBase implements iStoredModel, iIdentityProvider
{
	use tPdoModel;

    /**
     * Ensures the password has been hashed with SHA1
     *
     * @throws \Cougar\Exceptions\AccessDeniedException
     */
    public function __preValidate()
    {
        // See if we are inserting a record
        if ($this->__insertMode)
        {
            // Hash the password
            $this->password = sha1($this->password);

            // Make sure the user is not giving admin rights to his/herself
            if ($this->admin == true &&
                ! $this->__security->getIdentity()->admin)
            {
                throw new AccessDeniedException(
                    "You are not authorized to create this record");
            }
        }
        else
        {
            if ($this->password !== $this->__defaultValues["password"])
            {
                $this->password = sha1($this->password);
            }
        }
    }

    /**
     * Authorize the user by verifying the username and password. If the
     * username/password don't match, null will be returned. If for some
     * unexplained reason more than one record is returned during the query
     * and null identity will be returned as well.
     *
     * @param \CougarTutorial\Security\Credentials $credentials
     *  Instance of UsernamePasswordCredentials
     * @return array Identity parameters
     */
    public function getIdentity(Credentials $credentials)
    {
        if ($credentials instanceof UsernamePasswordCredentials)
        {
            // The authorization context will disable queries when an identity
            // is not present; re-enable queries temporarily to make sure we can
            // validate the credentials
            $allow_query = $this->__allowQuery;
            $this->__allowQuery = true;

            // Store the current view and set the identity view
            $view = $this->__currentView;
            $this->__setView("identity");

            // Perform the query
            $user_list = $this->query(array(
                    new QueryParameter("username", $credentials->username),
                    new QueryParameter("password", sha1($credentials->password))
                ), "CougarTutorial\\Models\\User", array(null, "identity"));

            // Reset the allow query permission and view
            $this->__allowQuery = $allow_query;
            $this->__setView($view);

            // If authentication was successful, we should get one record;
            // no more, no less
            if (count($user_list) == 1)
            {
                // Grab the identity record and add the id parameter
                $identity = $user_list[0]->__toArray();
                $identity["id"] = $identity["emailAddress"];

                return $identity;
            }
        }
    }

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
        $auth_query->object = &$this;

        // Make the authorization query
        $security->authorize("user.model", $auth_query);
    }
}
?>
