<?php

namespace CougarTutorial;

use Cougar\Autoload\FlexAutoload;
use Cougar\Security\iSecurity;

/**
 * Gets information about U.S. states.
 *
 * @package CougarTutorial
 */
class State implements iState
{
    /**
     * Initializes the object by requiring the Security context and Model
     * factory
     *
     * @param iSecurity $security
     *   Security context
     * @param ModelFactory $factory
     *   Model factory
     */
    public function __construct(iSecurity $security,
        ModelFactory $factory)
    {
        // Store the reference to the security context and model factory
        $this->security = $security;
        $this->factory = $factory;
    }


    /***************************************************************************
     * PUBLIC PROPERTIES AND METHODS
     **************************************************************************/

    /**
     * Gets the list of U.S. States
     *
     * @Path /state
     * @Methods GET
     * @GetQuery query
     * @XmlRootElement states
     * @XmlObjectName state
     *
     * @param array $query
     *   List of query parameters
     * @return array List of states that match criteria
     */
    public function getStateList(array $query = array())
    {
        $state_model = $this->factory->StatePdo();
        return $state_model->query($query);
    }

    /**
     * Gets the specified U.S. State
     *
     * @Path /state/:id
     * @Methods GET
     * @XmlRootElement state
     *
     * @param string $id
     * @return \CougarTutorial\Models\State
     */
    public function getState($id)
    {
        // Get the state model for the given state
        $state_model = $this->factory->StatePdo(array("id" => $id));

        // Return the model in non-persistent mode
        $state_model->endPersistence();
        return $state_model;
    }


    /***************************************************************************
     * PROTECTED PROPERTIES AND METHODS
     **************************************************************************/

    /**
     * @var \Cougar\Security\iSecurity Security context
     */
    protected $security;

    /**
     * @var ModelFactory Model factory
     */
    protected $factory;
}
