<?php

namespace CougarTutorial\Models;

use Cougar\Model\iStoredModel;
use Cougar\Model\tPdoModel;

/**
 * Defines the State PDO model.
 *
 * @Table State
 * @Allow READ QUERY
 * @PrimaryKey stateId
 * @QueryView list
 * @CacheTime 3600
 */
class StatePdo extends StateBase implements iStoredModel
{
	use tPdoModel;
}
?>
