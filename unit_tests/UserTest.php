<?php
namespace CougarTutorial\UnitTests;

use CougarTutorial\User;
use Cougar\Security\Identity;

// Initialize the application
require_once(__DIR__ . "/../init.php");

class UserTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Cougar\Security\Security
     */
    protected $security;

    /**
     * @var User
     */
    protected $object;

    protected function setUp()
    {
        $this->security = $this->getMock("\\Cougar\\Security\\Security");
        $this->object = new User($this->security);
    }

    /**
     * @covers CougarTutorial\User::greet
     */
    public function testGreet()
    {
        $this->assertEquals("Welcome, visitor", $this->object->greet());
    }

    /**
     * @covers CougarTutorial\User::greet
     */
    public function testGreetWithIdentity()
    {
        $identity = new Identity("unit_test", array("givenName" => "John"));

        $this->security->expects($this->once())
            ->method("isAuthenticated")
            ->will($this->returnValue(true));
        $this->security->expects($this->once())
            ->method("getIdentity")
            ->will($this->returnValue($identity));

        $this->assertEquals("Welcome, John", $this->object->greet());
    }

    /**
     * @covers CougarTutorial\User::createUser
     * @todo   Implement testCreateUser().
     */
    public function testCreateUser()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers CougarTutorial\User::getUser
     * @todo   Implement testGetUser().
     */
    public function testGetUser()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers CougarTutorial\User::updateUser
     * @todo   Implement testUpdateUser().
     */
    public function testUpdateUser()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers CougarTutorial\User::deleteUser
     * @todo   Implement testDeleteUser().
     */
    public function testDeleteUser()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}
