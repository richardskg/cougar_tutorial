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
    // return 'filler so we can see if the non-breaking spaces are working. ✔ § µ '.json_decode('"\u00a0\u00a0\u00a0\u00a0"').'<-nbsp x4';
    $sql_statement = "SELECT id, SimpleString, Count " .
      "FROM CharTest " .
      "WHERE id = :id ";

    // Prepare and execute the statement
    $statement = $this->__pdo->prepare($sql_statement);
    $statement->execute(array("id" => $id));


    // Get the results
    $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

    // $results = $this->__pdo->query($sql_statement)->fetchAll(\PDO::FETCH_ASSOC);

    echo '<p>count($result): ' . count($results);

    if (count($results) > 0) {
      // Grab the identity record and add the id parameter
      echo 'count($results): ' . count($results) .'';
      $charTest = $results[0];

      return $charTest['SimpleString'];
    } else {
      $simpleString = 'filler so we can see if the non-breaking spaces are working. ✔ § µ ' . json_decode('"\u00a0\u00a0\u00a0\u00a0"') . '<-nbsp x4';

      $this->id = $id;
      $this->SimpleString = $simpleString;
      $this->Count = 1;

      echo '<p> about to save';

      // Save the record
      $this->save();

      echo '<p> calling $pdo->commit';

      $this->__pdo->commit();
      echo '<p>simple->save() finished...';
      return $this->SimpleString;
    }


  }

  public function encodeString(string $source): string
  {
    $encoded = json_encode($source);
    $encoded = substr($encoded, 1);
    $encoded = substr($encoded, 0, -1);
    return $encoded;
  }

  public function decodeString(string $source): string
  {
    return json_decode(('"' . $source . '"'), true);
  }
}
?>