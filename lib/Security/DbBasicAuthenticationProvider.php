<?php

namespace CougarTutorial\Security;

use Cougar\Security\iAuthenticationProvider;
use Cougar\Security\iIdentity;
use Cougar\Security\Identity;
use Cougar\RestService\iRestService;
use Cougar\Exceptions\AuthenticationRequiredException;

/**
 * Implements the Cougar Authentication Provider interface via HTTP Basic
 * Authentication. It verifies usernames and passwords and obtains identities
 * from the User model that implements the Identity interface.
 */
class DbBasicAuthenticationProvider implements iAuthenticationProvider
{
    /**
     * Accepts references to a Cougar RestService and an object implementing the
     * Identity interface.
     *
     * @param \Cougar\RestService\iRestService $rest_service
     *   RestService object used to retrieve Authorization header
     * @param iIdentityProvider $identity_provider
     *   Identity provider object that will validate and obtain the identity
     */
    public function __construct(iRestService $rest_service,
        iIdentityProvider $identity_provider)
    {
        // Store the references
        $this->restService = $rest_service;
        $this->identityProvider = $identity_provider;
    }


    /***************************************************************************
     * PUBLIC PROPERTIES AND METHODS
     **************************************************************************/

    /**
     * Authenticates the client. If authentication is successful, the method
     * will return an object that implements the iIdentity object. If
     * authentication fails, the method should return a null.
     *
     * @return iIdentity Identity object
     * @throws \Cougar\Exceptions\AuthenticationRequiredException
     */
    public function authenticate()
    {
        // PHP automatically parses the Authorization header for us; we just
        // need to check if the PHP_AUTH_USER key exists in the $_SERVER
        // superglobal
        if (array_key_exists("PHP_AUTH_USER", $_SERVER))
        {
            // Get the credentials
            $credentials = new UsernamePasswordCredentials();
            $credentials->username = $_SERVER["PHP_AUTH_USER"];
            $credentials->password = $_SERVER["PHP_AUTH_PW"];

            // Get the identity from the credentials
            $identity = $this->identityProvider->getIdentity($credentials);

            if ($identity)
            {
                // Return the identity object
                return new Identity($identity["id"], $identity);
            }
            else
            {
                // Invalid credentials; throw an exception
                throw new AuthenticationRequiredException(
                    "Invalid username/password");
            }
        }
    }


    /***************************************************************************
     * PROTECTED PROPERTIES AND METHODS
     **************************************************************************/

    /**
     * @var \Cougar\RestService\iRestService Rest service
     */
    protected $restService = null;

    /**
     * @var iIdentityProvider Identity provider
     */
    protected $identityProvider = null;
}
?>
