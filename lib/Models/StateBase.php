<?php

namespace CougarTutorial\Models;

/**
 * Defines the properties and data constraints of the State table.
 * 
 * @CaseInsensitive
 * @Views list
 */
abstract class StateBase
{
	/**
	 * @Alias id
	 * @Alias abbreviation
     * @Alias postalCode
	 * @Column StateID
	 * @NotNull
	 * @var string State abbreviation/postal code
	 */
	public $stateId = null;

	/**
	 * @Column Name
	 * @NotNull
	 * @var string State name
	 */
	public $name = null;

	/**
	 * @Column Capital
	 * @View list hidden
	 * @var string State's capital city
	 */
	public $capital = null;

	/**
	 * @Column LargestCity
	 * @View list hidden
	 * @var string State's largest city (by population)
	 */
	public $largestCity = null;

	/**
	 * @Column UnionDate
	 * @View list hidden
	 * @DateTimeFormat date
	 * @var DateTime Date when state joined the union
	 */
	public $unionDate = null;

	/**
	 * @Column LandArea
	 * @View list hidden
	 * @var int Land area in sq. miles
	 */
	public $landArea = null;

	/**
	 * @Column Counties
	 * @View list hidden
	 * @var int Number of counties in the state
	 */
	public $counties = null;

	/**
	 * @Column Population
	 * @View list hidden
	 * @var int State's population
	 */
	public $population = null;
}
?>
