<?php

namespace CougarTutorial\Characters;

use Cougar\Model\PdoModel;
/**
 * Defines the properties and data constraints of the State table.
 * 
 * @CaseInsensitive
 * @Views identity
 */
abstract class CharTestBase extends PdoModel
{

	/**
	 * @Column id
	 * @NotNull
   * @PrimaryKey
	 * @var int Simple integer used as id.
	 */
	public $id = null;

  /**
	 * @Column SimpleString
	 * @NotNull
	 * @var string The string possible containing special characters.
	 */
	public $SimpleString = null;

	/**
	 * @Column Count
	 * @NotNull
	 * @var int Count of accesses.
	 */
	public $Count = null;


}
?>
