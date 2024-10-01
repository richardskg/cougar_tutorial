<?php

namespace CougarTutorial\Characters;

use CougarTutorial\Characters\CharTestBase;
use Cougar\Util\QueryParameter;
use CougarTutorial\Security\UserModelAuthorizationProvider;
use Cougar\Security\Security;
use Cougar\Cache\CacheFactory;
use Cougar\PDO\PDO;
use CougarTutorial\Security\iIdentityProvider;
use Cougar\Model\tPdoModel;

/**
 * Defines the User PDO model.
 *
 * @Table CharTest
 * @Allow CREATE READ UPDATE DELETE QUERY
 * @PrimaryKey id
 * @CacheTime 3600
 */
class CharTestPdo extends CharTestBase
{
  use tPdoModel;

  public function getSampleString(int $id): string
  {

    $simpleString = 'filler so we can see if the non-breaking spaces are working. ✔ § µ ' .
    json_decode('"\u00a0\u00a0\u00a0\u00a0"') . '<-nbsp x4';

    // // Perform the query
    // // This will return an array of CharTest. There should only be one since we 
    // // search by id.
    // $charTest = $this->query(array(
    //   new QueryParameter("id", $id)
    // ), "CougarTutorial\\Characters\\CharTest");

    // // $results will be a CharTest object.
    // $results = null;
    // if (count($charTest) == 1) {
    //   $results = $charTest[0];
    // }

    // if ($results) {
    //   return $this->decodeString($results->SimpleString);
    // } else {
    //   $simpleString = 'filler so we can see if the non-breaking spaces are working. ✔ § µ ' .
    //                   json_decode('"\u00a0\u00a0\u00a0\u00a0"') . '<-nbsp x4';

    //   $this->id = $id;
    //   $this->SimpleString = $simpleString;
    //   $this->Count = 1;
    //   // Save the record
    //   $this->save();
    //   $this->__pdo->commit();
    //   // Now read it back so we can return the same as a query
    //   $charTest = $this->query(array(
    //     new QueryParameter("id", $id)
    //   ), "CougarTutorial\\Characters\\CharTest");

    //   return $this->decodeString($charTest[0]->SimpleString);
    // }
    return $simpleString;
  }

  public function encodeString(string $source): string
  {
    $encoded = json_encode($source);

    // Remove the leading and traling " that json_encode adds
    $encoded = substr($encoded, 1);
    $encoded = substr($encoded, 0, -1);
    return $encoded;
  }

  public function decodeString(string $source): string
  {
    // We need to add leading and trailing " so json_decode will recognize the string.
    return json_decode(('"' . $source . '"'), true);
  }
}
?>