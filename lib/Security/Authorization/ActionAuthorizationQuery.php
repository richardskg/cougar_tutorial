<?php

namespace CougarTutorial\Security;

use Cougar\Model\Struct;

/**
 * Defines the properties of a Cougar Tutorial's Authorization query
 */
class ActionAuthorizationQuery extends Struct
{
    /**
     * The action being performed (one of create, read, update, or delete
     *
     * @var string
     */
    public $action;

    /**
     * @var string Object type (one of state, user or visited)
     */
    public $type;

    /**
     * @var object The object on which to perform the action
     */
    public $object;
}
?>
