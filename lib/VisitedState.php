<?php

namespace CougarTutorial;

use Cougar\Security\iSecurity;
use Cougar\Exceptions\AccessDeniedException;

/**
 * Manages visited states, allowing the declaration, deletion and querying of
 * states that have been visited by users.
 *
 * @package CougarTutorial
 */
class VisitedState implements iVisitedState
{
    /**
     * Stores reference to the security context and model factory
     *
     * @param iSecurity $security
     *   Security context
     * @param ModelFactory $factory
     *   Model factory
     */
    public function __construct(iSecurity $security, ModelFactory $factory)
    {
        // Store the reference to the security context
        $this->security = $security;
        $this->factory = $factory;
    }


    /***************************************************************************
     * PUBLIC PROPERTIES AND METHODS
     **************************************************************************/

    /**
     * Sets the state as visited by the identity id or, if identity has admin
     * rights, the given userId
     *
     * @Path /user/visited/:state_id
     * @Path /state/:state_id/visited
     * @Methods POST PUT
     * @GetParam user_id
     * @Authentication required
     *
     * @param string $state_id
     *   State abbreviation that was visited
     * @param string $user_id
     *   Optional userId for administrators
     * @throws \Cougar\Exceptions\AccessDeniedException
     */
    public function stateVisited($state_id, $user_id = null)
    {
        // Make sure we are authenticated
        if (! $this->security->isAuthenticated())
        {
            throw new AccessDeniedException(
                "You do not have rights to this resource");
        }

        // Get a new entry record
        $entry = $this->factory->VisitedStatePdo();

        // Make sure the state is valid
        $state = $this->factory->StatePdo(array("stateId" => $state_id));
        $entry->stateId = $state->stateId;

        // See if identity is an administrator
        if ($this->security->getIdentity()->admin && $user_id)
        {
            // Make sure the user exists in the system
            $user = $this->factory->UserPdo(array("id" => $user_id));

            // Add this user's id to the entry
            $entry->userId = $user->id;
        }
        else
        {
            // Add the user in the identity
            $entry->userId = $this->security->getIdentity()->id;
        }

        // Save the entry
        $entry->save();
    }

    /**
     * Declares that a state has not bee visited by the user
     *
     * @Path /user/visited/:state_id
     * @Path /state/:state_id/visited
     * @Methods DELETE
     * @GetParam user_id
     * @Authentication required
     *
     * @param string $state_id
     *   State abbreviation that was visited
     * @param string $user_id
     *   Optional userId for administrators
     * @throws \Cougar\Exceptions\AccessDeniedException
     */
    public function stateNotVisited($state_id, $user_id = null)
    {
        // Make sure we are authenticated
        if (! $this->security->isAuthenticated())
        {
            throw new AccessDeniedException(
                "You do not have rights to this resource");
        }

        // Make sure the state is valid
        $state = $this->factory->StatePdo(array("stateId" => $state_id));
        $state_id = $state->stateId;

        // Get the user_id
        if ($this->security->getIdentity()->admin && $user_id)
        {
            // Make sure the user exists in the system
            $user = $this->factory->UserPdo(array("id" => $user_id));
            $user_id = $user->id;
        }
        else
        {
            // Add the user in the identity
            $user_id = $this->security->getIdentity()->id;
        }

        // Get the entry and delete it
        $entry = $this->factory->VisitedStatePdo(
            array("stateId" => $state_id, "userId" => $user_id));
        $entry->delete();
    }

    /**
     * Gets the list of states that have been visited by the given user. If
     * userId is omitted, it will return the list of states visited by the
     * authenticated identity.
     *
     * @Path /user/visited
     * @Methods GET
     * @GetParam user_id
     * @Authentication required
     * @XmlRootElement states
     * @XmlElement state
     *
     * @param string $user_id
     *   userId to use (optional)
     * @return array visited states
     * @throws \Cougar\Exceptions\AccessDeniedException
     */
    public function getVisitedStatesByUser($user_id = null)
    {
        // Make sure we are authenticated
        if (! $this->security->isAuthenticated())
        {
            throw new AccessDeniedException(
                "You do not have rights to this resource");
        }

        // See if an ID was provided
        if (! $user_id)
        {
            $user_id = $this->security->getIdentity()->id;
        }

        // Get the model and query it
        $visited_states = $this->factory->VisitedStatePdo();
        return $visited_states->queryVisited($user_id);
    }

    /**
     * Gets the list of users that have visited a particular state. If the
     * identity has been authenticated, user information will be retrieved.
     * Otherwise, only the visitor count will be retrieved.
     *
     * @Path /state/:state_id/visitors
     * @Methods GET
     * @GetParam user_id
     * @Authentication optional
     * @XmlRootElement visitors
     * @XmlElement visitor
     *
     * @param string $state_id
     *   State ID to get information from
     * @return mixed Users that have visited the state
     */
    public function getStateVisitors($state_id)
    {
        // Get the model
        $visited_states = $this->factory->VisitedStatePdo();

        // See if identity has authenticated
        if ($this->security->isAuthenticated())
        {
            return $visited_states->queryVisitors($state_id);
        }
        else
        {
            return $visited_states->queryVisitorCount($state_id);
        }
    }


    /***************************************************************************
     * PROTECTED PROPERTIES AND METHODS
     **************************************************************************/

    /**
     * @var \Cougar\Security\iSecurity $security;
     */
    protected $security;

    /**
     * @var ModelFactory Model factory
     */
    protected $factory;
}
?>
