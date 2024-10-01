<?php 

namespace CougarTutorial;

use CougarTutorial\Characters\CharTestPdo;
use CougarTutorial\Models\UserPdo;
use CougarTutorial\Security\UserModelAuthorizationProvider;
use Cougar\Security\Security;
use Cougar\Cache\CacheFactory;
use Cougar\PDO\PDO;
use CougarTutorial\Characters\CharClass;
use Cougar\PDO\PDOFactory;

require_once(__DIR__ . "/../init.php");
// Create a new Security context
$security = new Security();

// Add the User Model authorization provider
$security->addAuthorizationProvider(new UserModelAuthorizationProvider());

// Create the application cache object
$cache = CacheFactory::getApplicationCache();

// Create the database connection
// $pdo = new PDO("sqlite:" . __DIR__ . "/../db/cougar_tutorial.db");
$pdo = new PDO('mysql:dbname=cougar_tutorial;host=127.0.0.1', "root");
// $pdo = new PDO('mysql:dbname=cougar_tutorial_ascii;host=127.0.0.1', "root");
// PDO::usePdoViaOci8(true);
// $pdo = new PDO('oci:dbname=//cman-dev.byu.edu:31200/cescpy1.byu.edu', "name", "password");
// $pdo = PDOFactory::getConnection("ces");

// // Create a new UserPdo object
$simple = new CharTestPdo($security, $cache, $pdo, null,
                          null, true, "unknown");

$chars = $simple->getSampleString(1);
$encoded_chars =  $simple->encodeString($chars);
$decoded_chars = $simple->decodeString($simple->decodeString($encoded_chars));
?>

<html>
<body>
<h1>Welcome</h1>
<div>Special Chars: <?=  $chars ?></div>
<div>json_encoded:  <?=  $encoded_chars ?></div>
<div>json_decoded:  <?=  $decoded_chars ?></div>
</body>
</html>