<?php
namespace CougarTutorial\UnitTests;

use CougarTutorial\User;

// Initialize the application
require_once(__DIR__ . "/../init.php");

class UserTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var User
     */
    protected $object;

    protected function setUp()
    {
        $security = $this->getMock("\\Cougar\\Security\\Security");
        $this->object = new User($security);
    }

    /**
     * @covers CougarTutorial\User::greet
     */
    public function testGreet()
    {
        $this->assertEquals("Welcome, visitor", $this->object->greet());
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
