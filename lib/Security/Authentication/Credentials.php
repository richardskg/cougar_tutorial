<?php

namespace CougarTutorial\Security;

/**
 * Defines an abstract and empty Credentials class. This class should be
 * extended to include the properties required for a particular set of
 * credentials. That class is then instantiated and passed to an implementation
 * of an Identity Provider to verify the credentials and return the proper
 * identity.
 */
abstract class Credentials
{

}
?>