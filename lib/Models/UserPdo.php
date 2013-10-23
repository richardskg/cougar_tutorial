<?php

namespace CougarTutorial\Models;

use Cougar\Model\iStoredModel;
use Cougar\Model\tPdoModel;
use Cougar\Util\QueryParameter;
use CougarTutorial\Security\iIdentityProvider;
use CougarTutorial\Security\Credentials;
use CougarTutorial\Security\UsernamePasswordCredentials;

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
     */
    public function __preValidate()
    {
        // Make sure we are inserting a record
        if ($this->__insertMode)
        {
            $this->password = sha1($this->password);
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
            $user_list = $this->query(array(
                    new QueryParameter("username", $credentials->username),
                    new QueryParameter("password", sha1($credentials->password))
                ), "CougarTutorial\\Models\\User", array(null, "identity"));

            if (count($user_list) == 1)
            {
                // Grab the identity record and add the id parameter
                $identity = $user_list[0]->__toArray();
                $identity["id"] = $identity["emailAddress"];

                return $identity;
            }
        }
    }
}
?>
