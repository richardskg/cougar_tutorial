<?php

namespace CougarTutorial\Characters;

use Cougar\Model\iModel;
use Cougar\Model\tModel;

/**
 * Defines the User PDO model.
 *
 * @Table CharTest
 * @Allow CREATE READ UPDATE DELETE QUERY
 * @PrimaryKey id
 * @CacheTime 3600
 */
class CharTest extends CharTestBase
{
  use tModel;

}
?>