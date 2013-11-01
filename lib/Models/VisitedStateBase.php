<?php

namespace CougarTutorial\Models;

/**
 * Defines the properties and data constraints of the VisitedState table.
 * 
 * @CaseInsensitive
 */
abstract class VisitedStateBase
{
    /**
     * @Column Email
     * @NotNull
     * @Regex /^.+$/
     * @var string User ID
     */
    public $userId;

    /**
	 * @Column StateID
	 * @NotNull
     * @Regex /^[A-Z]{2}$/
	 * @var string State abbreviation/postal code
	 */
	public $stateId = null;
}
?>
