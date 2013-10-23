<?php
/**
 * Forces the user to authenticate
 */
namespace CougarTutorial;

use CougarTutorial\Models\UserPdo;
use CougarTutorial\Security\UsernamePasswordCredentials;
use Cougar\Security\Security;
use Cougar\Cache\CacheFactory;
use Cougar\PDO\PDO;

/**
 * Forces the browser to display the login screen.
 */
function showBrowserLogin()
{
    header("WWW-Authenticate: Basic realm=\"Cougar Tutorial Login\"");
    header("HTTP/1.1 401 Unauthorized");
    echo("401 Authentication required");
    exit();
}

if (! isset($_SERVER['PHP_AUTH_USER']))
{
    showBrowserLogin();
}
else
{
    // Initialize the application
    require_once(__DIR__ . "/../init.php");

    // Create a new Security context
    $security = new Security();

    // Create the application cache object
    $cache = CacheFactory::getApplicationCache();

    // Create the database connection
    $pdo = new PDO("sqlite:" . __DIR__ . "/../db/cougar_tutorial.db");

    // Create a new UserPdo object
    $user = new UserPdo($security, $cache, $pdo);

    // Get the username and password
    $credentials = new UsernamePasswordCredentials();
    $credentials->username = $_SERVER["PHP_AUTH_USER"];
    $credentials->password = $_SERVER["PHP_AUTH_PW"];

    // Attempt to get the identity
    $identity = $user->getIdentity($credentials);

    // See if we got the identity
    if ($identity === null)
    {
        // No identity; show the login screen again
        showBrowserLogin();
    }
}
?>
<html>
<body>
<h1>Welcome</h1>
<p>Welcome, <?= $identity["givenName"] ?>!</p>
</body>
</html>
