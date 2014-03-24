<?php

namespace CougarTutorial\UnitTests;

use Cougar\Util\QueryParameter;
use CougarTutorial\State;

require_once(__DIR__ . "/../init.php");

class StateTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \CougarTutorial\StateModelFactory State object to test
     */
    protected $factory;

    /**
     * @var \CougarTutorial\State State object to test
     */
    protected $object;

    protected function setUp()
    {
        // Create the Security context mock
        $security = $this->getMock("\\Cougar\\Security\\Security");

        // Create the StateModelFactory mock
        $this->factory = $this->getMockBuilder(
                "\\CougarTutorial\\ModelFactory")
            ->disableOriginalConstructor()
            ->getMock();

        // Create the object
        $this->object = new State($security, $this->factory);
    }

    /**
     * @covers \CougarTutorial\State::getStateList
     */
    public function testGetStateListNoQuery()
    {
        $state_pdo_model = $this->getMockBuilder(
                "\\CougarTutorial\\Models\\StatePdo")
            ->disableOriginalConstructor()
            ->getMock();
        $state_pdo_model->expects($this->once())
            ->method("query")
            ->with(array())
            ->will($this->returnValue(array()));

        $this->factory->expects($this->once())
            ->method("StatePdo")
            ->with()
            ->will($this->returnValue($state_pdo_model));
        $this->factory->expects($this->never())
            ->method("State");

        $state_list = $this->object->getStateList();
        $this->assertCount(0, $state_list);
    }

    /**
     * @covers \CougarTutorial\State::getStateList
     */
    public function testGetStateListWithQuery()
    {
        $query_params = array(new QueryParameter("abc", "123"));

        $state_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\StatePdo")
            ->disableOriginalConstructor()
            ->getMock();
        $state_pdo_model->expects($this->once())
            ->method("query")
            ->with($query_params)
            ->will($this->returnValue(array()));

        $this->factory->expects($this->once())
            ->method("StatePdo")
            ->with(null, null, true)
            ->will($this->returnValue($state_pdo_model));
        $this->factory->expects($this->never())
            ->method("State");

        $state_list = $this->object->getStateList($query_params);
        $this->assertCount(0, $state_list);
    }

    /**
     * @covers \CougarTutorial\State::getState
     */
    public function testGetState()
    {
        $params = array("id" => "UT");

        $state_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\StatePdo")
            ->disableOriginalConstructor()
            ->getMock();

        $state_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\State")
            ->disableOriginalConstructor()
            ->getMock();

        $this->factory->expects($this->once())
            ->method("StatePdo")
            ->with($params, null, true)
            ->will($this->returnValue($state_pdo_model));

        $state = $this->object->getState("UT");
        $this->assertInstanceOf("\\CougarTutorial\\Models\\StatePdo", $state);
    }
}
