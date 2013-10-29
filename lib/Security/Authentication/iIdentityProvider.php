<?php

namespace CougarTutorial\Security;

/**
 * Defines the Identity interface used to verify username and passwords and
 * obtain identity attributes.
 */
interface iIdentityProvider
{
    /**
     * Gets the user identity from the provided credentials object. If the
     * identity is successfully verified, the method must return an array with
     * identity attributes. If the identity is not verified, the method should
     * return null and not throw an exception.
     *
     * @param \CougarTutorial\Security\Credentials $credentials
     *  IdentityCredentials object with the identity credentials
     * @return array Identity information or null if verification fails
     */
    public function getIdentity(Credentials $credentials);
}
?>
