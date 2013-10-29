<?php

namespace CougarTutorial\Security;

use Cougar\Model\Struct;

/**
 * Defines the properties of a Cougar Tutorial's Model Authorization query. All
 * values must be passed as references.
 */
class ModelAuthorizationQuery extends Struct
{
    /**
     * @var bool Whether to allow read operations (reference)
     */
    public $allowCreate;

    /**
     * @var bool Whether to allow read operations (reference)
     */
    public $allowRead;

    /**
     * @var bool Whether to allow read operations (reference)
     */
    public $allowUpdate;

    /**
     * @var bool Whether to allow read operations (reference)
     */
    public $allowDelete;

    /**
     * @var bool Whether to allow read operations (reference)
     */
    public $allowQuery;

    /**
     * @var array Array with properties (keys) and columns (values) (reference)
     */
    public $columnMap;

    /**
     * @var array Whether properties are read-only (reference)
     */
    public $readOnly;

    /**
     * @var array Whether properties are visible (reference)
     */
    public $visible;

    /**
     * @var object Optional reference to the object
     */
    public $object;
}
?>
