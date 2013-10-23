<?php

namespace CougarTutorial\Models;

/**
 * Defines the properties and data constraints of the State table.
 * 
 * @CaseInsensitive
 * @Views identity
 */
abstract class UserBase
{
	/**
	 * @Column GivenName
	 * @NotNull
	 * @var string Person's given (first or preferred) name
	 */
	public $givenName = null;

	/**
	 * @Column LastName
	 * @NotNull
	 * @var string Person's last name (or surname)
	 */
	public $lastName = null;

	/**
     * @Alias id
     * @Alias userId
     * @Alias username
	 * @Column Email
     * @NotNull
	 * @var string
	 */
	public $emailAddress = null;

	/**
	 * @Column Password
     * @NotNull
	 * @View __default__ hidden
     * @View identity hidden readonly
	 * @var string User's password, hashed using sha1; set/change in plain text
	 */
	public $password = null;

	/**
	 * @Column Administrator
     * @NotNull
	 * @View __default__ hidden readonly
     * @View identity
	 * @var bool Whether the user has admin rights
	 */
	public $admin = false;
}
?>
