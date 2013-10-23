<?php

namespace CougarTutorial\Models;

use Cougar\Model\iModel;
use Cougar\Model\tModel;

/**
 * Defines the User model.
 */
class User extends UserBase implements iModel
{
	use tModel;
}
?>
