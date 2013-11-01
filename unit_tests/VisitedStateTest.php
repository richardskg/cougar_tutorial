<?php

namespace CougarTutorial\UnitTests;

use Cougar\Security\Identity;
use CougarTutorial\VisitedState;
use CougarTutorial\Security\ActionAuthorizationProvider;
use CougarTutorial\Security\VisitedStateModelAuthorizationProvider;
use Cougar\Security\Security;
use Cougar\Util\QueryParameter;

require_once(__DIR__ . "/../init.php");

class VisitedStateTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \CougarTutorial\ModelFactory Object factory
     */
    protected $factory;

    /**
     * @var \Cougar\Security\Security Security context
     */
    protected $security;

    /**
     * @var \CougarTutorial\VisitedState Object to test
     */
    protected $object;

    protected function setUp()
    {
        // Create the Security context mock
        $this->security = $this->getMockBuilder(
            "\\Cougar\\Security\\Security")
            ->disableOriginalConstructor()
            ->getMock();

        // Create the VisitedStateModelFactory mock
        $this->factory = $this->getMockBuilder(
                "\\CougarTutorial\\ModelFactory")
            ->disableOriginalConstructor()
            ->getMock();

        // Create the object
        $this->object = new VisitedState($this->security, $this->factory);
    }

    /**
     * @covers \CougarTutorial\VisitedState::stateVisited
     * @expectedException \Cougar\Exceptions\AccessDeniedException
     */
    public function testStateVisited()
    {
        $this->security->expects($this->once())
            ->method("isAuthenticated")
            ->will($this->returnValue(false));

        $visited_state_pdo_model = $this->getMockBuilder(
                "\\CougarTutorial\\Models\\VisitedStatePdo")
            ->disableOriginalConstructor()
            ->getMock();
        $visited_state_pdo_model->expects($this->never())
            ->method("save");

        $this->factory->expects($this->never())
            ->method("VisitedStatePdo");

        $this->object->stateVisited("CA");
    }

    /**
     * @covers \CougarTutorial\VisitedState::stateVisited
     */
    public function testStateVisitedWithIdentity()
    {
        $identity = new Identity("john.doe@example.com",
            array("admin" => false));

        $this->security->expects($this->once())
            ->method("isAuthenticated")
            ->will($this->returnValue(true));
        $this->security->expects($this->any())
            ->method("getIdentity")
            ->will($this->returnValue($identity));

        $visited_state_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\VisitedStatePdo")
            ->disableOriginalConstructor()
            ->getMock();
        $visited_state_pdo_model->expects($this->once())
            ->method("save");

        $state = new \stdClass();
        $state->stateId = "CA";

        $this->factory->expects($this->once())
            ->method("VisitedStatePdo")
            ->with()
            ->will($this->returnValue($visited_state_pdo_model));
        $this->factory->expects($this->once())
            ->method("StatePdo")
            ->with(array("stateId" => "CA"))
            ->will($this->returnValue($state));

        $this->object->stateVisited("CA");
        $this->assertEquals($identity->id, $visited_state_pdo_model->userId);
        $this->assertEquals("CA", $visited_state_pdo_model->stateId);
    }

    /**
     * @covers \CougarTutorial\VisitedState::stateVisited
     */
    public function testStateVisitedWithIdentityAltUserId()
    {
        $identity = new Identity("john.doe@example.com",
            array("admin" => false));

        $this->security->expects($this->once())
            ->method("isAuthenticated")
            ->will($this->returnValue(true));
        $this->security->expects($this->any())
            ->method("getIdentity")
            ->will($this->returnValue($identity));

        $visited_state_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\VisitedStatePdo")
            ->disableOriginalConstructor()
            ->getMock();
        $visited_state_pdo_model->expects($this->once())
            ->method("save");

        $state = new \stdClass();
        $state->stateId = "CA";

        $this->factory->expects($this->once())
            ->method("VisitedStatePdo")
            ->with()
            ->will($this->returnValue($visited_state_pdo_model));
        $this->factory->expects($this->once())
            ->method("StatePdo")
            ->with(array("stateId" => "CA"))
            ->will($this->returnValue($state));

        $this->object->stateVisited("CA", "jane.doe@example.com");
        $this->assertEquals($identity->id, $visited_state_pdo_model->userId);
        $this->assertEquals("CA", $visited_state_pdo_model->stateId);
    }

    /**
     * @covers \CougarTutorial\VisitedState::stateVisited
     */
    public function testStateVisitedWithAdminIdentity()
    {
        $identity = new Identity("admin@example.com", array("admin" => true));

        $this->security->expects($this->once())
            ->method("isAuthenticated")
            ->will($this->returnValue(true));
        $this->security->expects($this->any())
            ->method("getIdentity")
            ->will($this->returnValue($identity));

        $visited_state_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\VisitedStatePdo")
            ->disableOriginalConstructor()
            ->getMock();
        $visited_state_pdo_model->expects($this->once())
            ->method("save");

        $state = new \stdClass();
        $state->stateId = "CA";

        $this->factory->expects($this->once())
            ->method("VisitedStatePdo")
            ->with()
            ->will($this->returnValue($visited_state_pdo_model));
        $this->factory->expects($this->once())
            ->method("StatePdo")
            ->with(array("stateId" => "CA"))
            ->will($this->returnValue($state));

        $this->object->stateVisited("CA");
        $this->assertEquals($identity->id, $visited_state_pdo_model->userId);
        $this->assertEquals("CA", $visited_state_pdo_model->stateId);
    }

    /**
     * @covers \CougarTutorial\VisitedState::stateVisited
     */
    public function testStateVisitedWithAdminIdentityAltUserId()
    {
        $identity = new Identity("admin@example.com", array("admin" => true));

        $this->security->expects($this->once())
            ->method("isAuthenticated")
            ->will($this->returnValue(true));
        $this->security->expects($this->any())
            ->method("getIdentity")
            ->will($this->returnValue($identity));

        $visited_state_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\VisitedStatePdo")
            ->disableOriginalConstructor()
            ->getMock();
        $visited_state_pdo_model->expects($this->once())
            ->method("save");

        $state = new \stdClass();
        $state->stateId = "CA";

        $user = new \stdClass();
        $user->id = "jane.doe@example.com";

        $this->factory->expects($this->once())
            ->method("VisitedStatePdo")
            ->with()
            ->will($this->returnValue($visited_state_pdo_model));
        $this->factory->expects($this->once())
            ->method("StatePdo")
            ->with(array("stateId" => "CA"))
            ->will($this->returnValue($state));
        $this->factory->expects($this->once())
            ->method("UserPdo")
            ->with(array("id" => "jane.doe@example.com"))
            ->will($this->returnValue($user));

        $this->object->stateVisited("CA", "jane.doe@example.com");
        $this->assertEquals("jane.doe@example.com",
            $visited_state_pdo_model->userId);
        $this->assertEquals("CA", $visited_state_pdo_model->stateId);
    }

    /**
     * @covers \CougarTutorial\VisitedState::stateNotVisited
     * @expectedException \Cougar\Exceptions\AccessDeniedException
     */
    public function testStateNotVisited()
    {
        $this->security->expects($this->once())
            ->method("isAuthenticated")
            ->will($this->returnValue(false));

        $visited_state_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\VisitedStatePdo")
            ->disableOriginalConstructor()
            ->getMock();
        $visited_state_pdo_model->expects($this->never())
            ->method("delete");

        $this->factory->expects($this->never())
            ->method("VisitedStatePdo");

        $this->object->stateNotVisited("CA");
    }

    /**
     * @covers \CougarTutorial\VisitedState::stateNotVisited
     */
    public function testStateNotVisitedWithIdentity()
    {
        $identity = new Identity("john.doe@example.com",
            array("admin" => false));

        $this->security->expects($this->once())
            ->method("isAuthenticated")
            ->will($this->returnValue(true));
        $this->security->expects($this->any())
            ->method("getIdentity")
            ->will($this->returnValue($identity));

        $visited_state_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\VisitedStatePdo")
            ->disableOriginalConstructor()
            ->getMock();
        $visited_state_pdo_model->expects($this->once())
            ->method("delete");

        $state = new \stdClass();
        $state->stateId = "CA";

        $this->factory->expects($this->once())
            ->method("VisitedStatePdo")
            ->with()
            ->will($this->returnValue($visited_state_pdo_model));
        $this->factory->expects($this->once())
            ->method("StatePdo")
            ->with(array("stateId" => "CA"))
            ->will($this->returnValue($state));

        $this->object->stateNotVisited("CA");
    }

    /**
     * @covers \CougarTutorial\VisitedState::stateNotVisited
     */
    public function testStateNotVisitedWithIdentityAltUserId()
    {
        $identity = new Identity("john.doe@example.com",
            array("admin" => false));

        $this->security->expects($this->once())
            ->method("isAuthenticated")
            ->will($this->returnValue(true));
        $this->security->expects($this->any())
            ->method("getIdentity")
            ->will($this->returnValue($identity));

        $visited_state_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\VisitedStatePdo")
            ->disableOriginalConstructor()
            ->getMock();
        $visited_state_pdo_model->expects($this->once())
            ->method("delete");

        $state = new \stdClass();
        $state->stateId = "CA";

        $this->factory->expects($this->once())
            ->method("VisitedStatePdo")
            ->with()
            ->will($this->returnValue($visited_state_pdo_model));
        $this->factory->expects($this->once())
            ->method("StatePdo")
            ->with(array("stateId" => "CA"))
            ->will($this->returnValue($state));

        $this->object->stateNotVisited("CA", "jane.doe@example.com");
    }

    /**
     * @covers \CougarTutorial\VisitedState::stateNotVisited
     */
    public function testStateNotVisitedWithAdminIdentity()
    {
        $identity = new Identity("admin@example.com", array("admin" => true));

        $this->security->expects($this->once())
            ->method("isAuthenticated")
            ->will($this->returnValue(true));
        $this->security->expects($this->any())
            ->method("getIdentity")
            ->will($this->returnValue($identity));

        $visited_state_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\VisitedStatePdo")
            ->disableOriginalConstructor()
            ->getMock();
        $visited_state_pdo_model->expects($this->once())
            ->method("delete");

        $state = new \stdClass();
        $state->stateId = "CA";

        $this->factory->expects($this->once())
            ->method("VisitedStatePdo")
            ->with()
            ->will($this->returnValue($visited_state_pdo_model));
        $this->factory->expects($this->once())
            ->method("StatePdo")
            ->with(array("stateId" => "CA"))
            ->will($this->returnValue($state));

        $this->object->stateNotVisited("CA");
    }

    /**
     * @covers \CougarTutorial\VisitedState::stateNotVisited
     */
    public function testStateNotVisitedWithAdminIdentityAltUserId()
    {
        $identity = new Identity("admin@example.com", array("admin" => true));

        $this->security->expects($this->once())
            ->method("isAuthenticated")
            ->will($this->returnValue(true));
        $this->security->expects($this->any())
            ->method("getIdentity")
            ->will($this->returnValue($identity));

        $visited_state_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\VisitedStatePdo")
            ->disableOriginalConstructor()
            ->getMock();
        $visited_state_pdo_model->expects($this->once())
            ->method("delete");

        $state = new \stdClass();
        $state->stateId = "CA";

        $user = new \stdClass();
        $user->id = "jane.doe@example.com";

        $this->factory->expects($this->once())
            ->method("VisitedStatePdo")
            ->with()
            ->will($this->returnValue($visited_state_pdo_model));
        $this->factory->expects($this->once())
            ->method("StatePdo")
            ->with(array("stateId" => "CA"))
            ->will($this->returnValue($state));
        $this->factory->expects($this->once())
            ->method("UserPdo")
            ->with(array("id" => "jane.doe@example.com"))
            ->will($this->returnValue($user));

        $this->object->stateNotVisited("CA", "jane.doe@example.com");
    }

    /**
     * @covers \CougarTutorial\VisitedState::getVisitedStatesByUser
     * @expectedException \Cougar\Exceptions\AccessDeniedException
     */
    public function testGetVisitedStatesByUser()
    {
        $this->security->expects($this->once())
            ->method("isAuthenticated")
            ->will($this->returnValue(false));

        $visited_state_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\VisitedStatePdo")
            ->disableOriginalConstructor()
            ->getMock();
        $visited_state_pdo_model->expects($this->never())
            ->method("queryVisited");

        $this->factory->expects($this->never())
            ->method("VisitedStatePdo");

        $this->object->getVisitedStatesByUser();
    }

    /**
     * @covers \CougarTutorial\VisitedState::getVisitedStatesByUser
     */
    public function testGetVisitedStatesByUserWithIdentity()
    {
        $identity = new Identity("john.doe@example.com",
            array("admin" => false));

        $this->security->expects($this->once())
            ->method("isAuthenticated")
            ->will($this->returnValue(true));
        $this->security->expects($this->any())
            ->method("getIdentity")
            ->will($this->returnValue($identity));

        $dummy_list = array("dummy list");

        $visited_state_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\VisitedStatePdo")
            ->disableOriginalConstructor()
            ->getMock();
        $visited_state_pdo_model->expects($this->once())
            ->method("queryVisited")
            ->with("john.doe@example.com")
            ->will($this->returnValue($dummy_list));

        $this->factory->expects($this->once())
            ->method("VisitedStatePdo")
            ->with()
            ->will($this->returnValue($visited_state_pdo_model));

        $list = $this->object->getVisitedStatesByUser();
        $this->assertEquals($dummy_list, $list);
    }

    /**
     * @covers \CougarTutorial\VisitedState::getVisitedStatesByUser
     */
    public function testGetVisitedStatesByUserWithIdentityAltUserId()
    {
        $identity = new Identity("john.doe@example.com",
            array("admin" => false));

        $this->security->expects($this->once())
            ->method("isAuthenticated")
            ->will($this->returnValue(true));
        $this->security->expects($this->any())
            ->method("getIdentity")
            ->will($this->returnValue($identity));

        $dummy_list = array("dummy list");

        $visited_state_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\VisitedStatePdo")
            ->disableOriginalConstructor()
            ->getMock();
        $visited_state_pdo_model->expects($this->once())
            ->method("queryVisited")
            ->with("jane.doe@example.com")
            ->will($this->returnValue($dummy_list));

        $this->factory->expects($this->once())
            ->method("VisitedStatePdo")
            ->with()
            ->will($this->returnValue($visited_state_pdo_model));

        $list = $this->object->getVisitedStatesByUser("jane.doe@example.com");
        $this->assertEquals($dummy_list, $list);
    }

    /**
     * @covers \CougarTutorial\VisitedState::getVisitedStatesByUser
     */
    public function testGetVisitedStatesByUserWithAdminIdentity()
    {
        $identity = new Identity("admin@example.com", array("admin" => true));

        $this->security->expects($this->once())
            ->method("isAuthenticated")
            ->will($this->returnValue(true));
        $this->security->expects($this->any())
            ->method("getIdentity")
            ->will($this->returnValue($identity));

        $dummy_list = array("dummy list");

        $visited_state_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\VisitedStatePdo")
            ->disableOriginalConstructor()
            ->getMock();
        $visited_state_pdo_model->expects($this->once())
            ->method("queryVisited")
            ->with("admin@example.com")
            ->will($this->returnValue($dummy_list));

        $this->factory->expects($this->once())
            ->method("VisitedStatePdo")
            ->with()
            ->will($this->returnValue($visited_state_pdo_model));

        $list = $this->object->getVisitedStatesByUser();
        $this->assertEquals($dummy_list, $list);
    }

    /**
     * @covers \CougarTutorial\VisitedState::getVisitedStatesByUser
     */
    public function testGetVisitedStatesByUserWithAdminIdentityAltUserId()
    {
        $identity = new Identity("admin@example.com", array("admin" => true));

        $this->security->expects($this->once())
            ->method("isAuthenticated")
            ->will($this->returnValue(true));
        $this->security->expects($this->any())
            ->method("getIdentity")
            ->will($this->returnValue($identity));

        $dummy_list = array("dummy list");

        $visited_state_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\VisitedStatePdo")
            ->disableOriginalConstructor()
            ->getMock();
        $visited_state_pdo_model->expects($this->once())
            ->method("queryVisited")
            ->with("jane.doe@example.com")
            ->will($this->returnValue($dummy_list));

        $this->factory->expects($this->once())
            ->method("VisitedStatePdo")
            ->with()
            ->will($this->returnValue($visited_state_pdo_model));

        $list = $this->object->getVisitedStatesByUser("jane.doe@example.com");
        $this->assertEquals($dummy_list, $list);
    }

    /**
     * @covers \CougarTutorial\VisitedState::getStateVisitors
     */
    public function testGetStateVisitors()
    {
        $this->security->expects($this->once())
            ->method("isAuthenticated")
            ->will($this->returnValue(false));

        $dummy_count = 283;

        $visited_state_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\VisitedStatePdo")
            ->disableOriginalConstructor()
            ->getMock();
        $visited_state_pdo_model->expects($this->once())
            ->method("queryVisitorCount")
            ->with("CA")
            ->will($this->returnValue($dummy_count));
        $visited_state_pdo_model->expects($this->never())
            ->method("queryVisitors");

        $this->factory->expects($this->once())
            ->method("VisitedStatePdo")
            ->with()
            ->will($this->returnValue($visited_state_pdo_model));

        $count = $this->object->getStateVisitors("CA");
        $this->assertEquals($dummy_count, $count);
    }

    /**
     * @covers \CougarTutorial\VisitedState::getStateVisitors
     */
    public function testGetStateVisitorsWithIdentity()
    {
        $identity = new Identity("john.doe@example.com",
            array("admin" => false));

        $this->security->expects($this->once())
            ->method("isAuthenticated")
            ->will($this->returnValue(true));
        $this->security->expects($this->any())
            ->method("getIdentity")
            ->will($this->returnValue($identity));

        $dummy_list = array("dummy list");

        $visited_state_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\VisitedStatePdo")
            ->disableOriginalConstructor()
            ->getMock();
        $visited_state_pdo_model->expects($this->never())
            ->method("queryVisitorCount");
        $visited_state_pdo_model->expects($this->once())
            ->method("queryVisitors")
            ->with("CA")
            ->will($this->returnValue($dummy_list));

        $this->factory->expects($this->once())
            ->method("VisitedStatePdo")
            ->with()
            ->will($this->returnValue($visited_state_pdo_model));

        $list = $this->object->getStateVisitors("CA");
        $this->assertEquals($dummy_list, $list);
    }

    /**
     * @covers \CougarTutorial\VisitedState::getStateVisitors
     */
    public function testGetStateVisitorsWithAdminIdentity()
    {
        $identity = new Identity("admin@example.com", array("admin" => true));

        $this->security->expects($this->once())
            ->method("isAuthenticated")
            ->will($this->returnValue(true));
        $this->security->expects($this->any())
            ->method("getIdentity")
            ->will($this->returnValue($identity));

        $dummy_list = array("dummy list");

        $visited_state_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\VisitedStatePdo")
            ->disableOriginalConstructor()
            ->getMock();
        $visited_state_pdo_model->expects($this->never())
            ->method("queryVisitorCount");
        $visited_state_pdo_model->expects($this->once())
            ->method("queryVisitors")
            ->with("CA")
            ->will($this->returnValue($dummy_list));

        $this->factory->expects($this->once())
            ->method("VisitedStatePdo")
            ->with()
            ->will($this->returnValue($visited_state_pdo_model));

        $list = $this->object->getStateVisitors("CA");
        $this->assertEquals($dummy_list, $list);
    }
}
?>
