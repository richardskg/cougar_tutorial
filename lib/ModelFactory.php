<?php

namespace CougarTutorial;

use Cougar\Security\iSecurity;
use Cougar\Cache\iCache;
use PDO;

/**
 * Returns new instances of model objects. To return instances of stored models
 * the security context, cache object and PDO connection must be passed in the
 * constructor.
 *
 * @package CougarTutorial
 */
class ModelFactory
{
    /**
     * Stores references to the security context, cache and database connections
     *
     * @param \Cougar\Security\iSecurity $security
     *   Security context
     * @param \Cougar\Cache\iCache $cache
     *   Cache object (optional)
     * @param \PDO
     *   Database connection (optional)
     */
    public function __construct(iSecurity $security = null,
        iCache $cache = null, PDO $pdo = null)
    {
        // Store the reference to the security context and model factory
        $this->security = $security;
        $this->cache = $cache;
        $this->pdo = $pdo;
    }


    /***************************************************************************
     * PUBLIC PROPERTIES AND METHODS
     **************************************************************************/

    /**
     * Returns a new instance of a State model
     *
     * @param mixed $object
     *   Import values from given object or assoc. array
     * @param string $view
     *   Set initial view
     * @param bool $strict
     *   Whether to be strict on import
     * @return \CougarTutorial\Models\State
     */
    public function State($object = null, $view = null, $strict = true)
    {
        return new Models\State($object, $view, $strict);
    }

    /**
     * Returns a new instance of a StatePdo Model
     *
     * @param mixed $object
     *   Import values from given object or assoc. array
     * @param string $view
     *   Set initial view
     * @param bool $strict
     *   Whether to be strict on import
     * @return \CougarTutorial\Models\StatePdo
     */
    public function StatePdo($object = null, $view = null, $strict = true)
    {
        return new Models\StatePdo($this->security, $this->cache, $this->pdo,
            $object, $view, $strict);
    }


    /***************************************************************************
     * PROTECTED PROPERTIES AND METHODS
     **************************************************************************/

    /**
     * @var \Cougar\Security\iSecurity Security context;
     */
    protected $security;

    /**
     * @var \Cougar\Cache\iCache Cache object
     */
    protected $cache;

    /**
     * @var \PDO Database connection
     */
    protected $pdo;
}
