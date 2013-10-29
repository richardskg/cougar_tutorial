<?php
namespace CougarTutorial\UnitTests;

use CougarTutorial\User;
use CougarTutorial\Models\User as UserModel;
use CougarTutorial\Security\ActionAuthorizationProvider;
use Cougar\Security\Identity;
use Cougar\Security\Security;
use Cougar\Security\iAuthenticationProvider;

// Initialize the application
require_once(__DIR__ . "/../init.php");

class UserTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Cougar\Security\Security
     */
    protected $security;

    /**
     * @var \CougarTutorial\ModelFactory State object to test
     */
    protected $factory;

    /**
     * @var User
     */
    protected $object;

    protected function setUp()
    {
        $this->security =new Security();

        // Add the authorization provider
        $this->security->addAuthorizationProvider(
            new ActionAuthorizationProvider());

        // Create the StateModelFactory mock
        $this->factory = $this->getMockBuilder(
            "\\CougarTutorial\\ModelFactory")
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new User($this->security, $this->factory);
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
        // Create a new identity with the information we want
        $identity = new Identity("john.doe@example.com",
            array("givenName" => "John"));

        // Add our test authentication provider
        $identity_provider = new UserTestIdentity($identity);
        $this->security->addAuthenticationProvider($identity_provider);
        $this->security->authenticate();

        $this->assertEquals("Welcome, John", $this->object->greet());
    }

    /**
     * @covers CougarTutorial\User::createUser
     */
    public function testCreateUser()
    {
        // Create the user object we will add
        $user = new UserModel;
        $user->givenName = "John";
        $user->lastName = "Doe";
        $user->emailAddress = "john.doe@example.com";
        $user->password = "Password";

        // Create the mock objects for the model and model factory
        $user_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\UserPdo")
            ->disableOriginalConstructor()
            ->getMock();
        $user_pdo_model->expects($this->once())
            ->method("__import")
            ->with($user);
        $user_pdo_model->expects($this->once())
            ->method("save");

        $this->factory->expects($this->once())
            ->method("UserPdo")
            ->with(null, null, true)
            ->will($this->returnValue($user_pdo_model));
        $this->factory->expects($this->once())
            ->method("User")
            ->with($user_pdo_model, null, true)
            ->will($this->returnValue($user));

        // Create the user
        $new_user = $this->object->createUser($user);
        $this->assertEquals($user, $new_user);
    }

    /**
     * @covers CougarTutorial\User::createUser
     * @expectedException \Cougar\Exceptions\AccessDeniedException
     */
    public function testCreateUserWithIdentity()
    {
        // Create a new identity with the information we want
        $identity = new Identity("nobody@example.com", array("admin" => false));

        // Add our test authentication provider
        $identity_provider = new UserTestIdentity($identity);
        $this->security->addAuthenticationProvider($identity_provider);
        $this->security->authenticate();

        // Create the user object we will add
        $user = new UserModel;
        $user->givenName = "John";
        $user->lastName = "Doe";
        $user->emailAddress = "john.doe@example.com";
        $user->password = "Password";

        // Create the mock objects for the model and model factory
        $user_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\UserPdo")
            ->disableOriginalConstructor()
            ->getMock();
        $user_pdo_model->expects($this->once())
            ->method("__import")
            ->with($user);
        $user_pdo_model->expects($this->never())
            ->method("save");

        $this->factory->expects($this->once())
            ->method("UserPdo")
            ->with(null, null, true)
            ->will($this->returnValue($user_pdo_model));
        $this->factory->expects($this->never())
            ->method("User");

        // Create the user
        $new_user = $this->object->createUser($user);
    }

    /**
     * @covers CougarTutorial\User::createUser
     */
    public function testCreateUserWithAdminIdentity()
    {
        // Create a new identity with the information we want
        $identity = new Identity("admin@example.com", array("admin" => true));

        // Add our test authentication provider
        $identity_provider = new UserTestIdentity($identity);
        $this->security->addAuthenticationProvider($identity_provider);
        $this->security->authenticate();

        // Create the user object we will add
        $user = new UserModel;
        $user->givenName = "John";
        $user->lastName = "Doe";
        $user->emailAddress = "john.doe@example.com";
        $user->password = "Password";

        // Create the mock objects for the model and model factory
        $user_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\UserPdo")
            ->disableOriginalConstructor()
            ->getMock();
        $user_pdo_model->expects($this->once())
            ->method("__import")
            ->with($user);
        $user_pdo_model->expects($this->once())
            ->method("save");

        $this->factory->expects($this->once())
            ->method("UserPdo")
            ->with(null, null, true)
            ->will($this->returnValue($user_pdo_model));
        $this->factory->expects($this->once())
            ->method("User")
            ->with($user_pdo_model, null, true)
            ->will($this->returnValue($user));

        // Create the user
        $new_user = $this->object->createUser($user);
        $this->assertEquals($user, $new_user);
    }

    /**
     * @covers CougarTutorial\User::getUser
     * @expectedException \Cougar\Exceptions\AccessDeniedException
     */
    public function testGetUser()
    {
        // Create the mock objects for the model and model factory
        $user_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\UserPdo")
            ->disableOriginalConstructor()
            ->getMock();

        $this->factory->expects($this->once())
            ->method("UserPdo")
            ->with(array("id" => "john.doe@example.com"), null, true)
            ->will($this->returnValue($user_pdo_model));
        $this->factory->expects($this->never())
            ->method("User");

        // Get the user
        $user = $this->object->getUser("john.doe@example.com");
    }

    /**
     * @covers CougarTutorial\User::getUser
     * @expectedException \Cougar\Exceptions\AccessDeniedException
     */
    public function testGetUserWithIdentity()
    {
        // Create a new identity with the information we want
        $identity = new Identity("nobody@example.com", array("admin" => false));

        // Add our test authentication provider
        $identity_provider = new UserTestIdentity($identity);
        $this->security->addAuthenticationProvider($identity_provider);
        $this->security->authenticate();

        // Create the mock objects for the model and model factory
        $user_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\UserPdo")
            ->disableOriginalConstructor()
            ->getMock();

        $this->factory->expects($this->once())
            ->method("UserPdo")
            ->with(array("id" => "john.doe@example.com"), null, true)
            ->will($this->returnValue($user_pdo_model));
        $this->factory->expects($this->never())
            ->method("User");

        // Get the user
        $user = $this->object->getUser("john.doe@example.com");
    }

    /**
     * @covers CougarTutorial\User::getUser
     */
    public function testGetUserWithUserIdentity()
    {
        // Create a new identity with the information we want
        $identity = new Identity("john.doe@example.com",
            array("admin" => false));

        // Add our test authentication provider
        $identity_provider = new UserTestIdentity($identity);
        $this->security->addAuthenticationProvider($identity_provider);
        $this->security->authenticate();

        // Create the user object we will return
        $user = new UserModel;
        $user->givenName = "John";
        $user->lastName = "Doe";
        $user->emailAddress = "john.doe@example.com";
        $user->password = "Password";

        // Create the mock objects for the model factory
        $this->factory->expects($this->once())
            ->method("UserPdo")
            ->with(array("id" => "john.doe@example.com"), null, true)
            ->will($this->returnValue($user));
        $this->factory->expects($this->once())
            ->method("User")
            ->with($user, null, true)
            ->will($this->returnValue($user));

        // Get the user
        $retrieved_user = $this->object->getUser("john.doe@example.com");
        $this->assertEquals($user, $retrieved_user);
    }

    /**
     * @covers CougarTutorial\User::getUser
     */
    public function testGetUserWithAdminIdentity()
    {
        // Create a new identity with the information we want
        $identity = new Identity("admin@example.com", array("admin" => true));

        // Add our test authentication provider
        $identity_provider = new UserTestIdentity($identity);
        $this->security->addAuthenticationProvider($identity_provider);
        $this->security->authenticate();

        // Create the user object we will return
        $user = new UserModel;
        $user->givenName = "John";
        $user->lastName = "Doe";
        $user->emailAddress = "john.doe@example.com";
        $user->password = "Password";

        // Create the mock objects for the model factory
        $this->factory->expects($this->once())
            ->method("UserPdo")
            ->with(array("id" => "john.doe@example.com"), null, true)
            ->will($this->returnValue($user));
        $this->factory->expects($this->once())
            ->method("User")
            ->with($user, null, true)
            ->will($this->returnValue($user));

        // Get the user
        $retrieved_user = $this->object->getUser("john.doe@example.com");
        $this->assertEquals($user, $retrieved_user);
    }

    /**
     * @covers CougarTutorial\User::updateUser
     * @expectedException \Cougar\Exceptions\AccessDeniedException
     */
    public function testUpdateUser()
    {
        // Create the user object we have allegedly modified
        $user = new UserModel;
        $user->givenName = "John";
        $user->lastName = "Doe";
        $user->emailAddress = "john.doe@example.com";
        $user->password = "Password";

        // Create the mock objects for the model and model factory
        $user_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\UserPdo")
            ->disableOriginalConstructor()
            ->getMock();
        $user_pdo_model->expects($this->never())
            ->method("save");

        $this->factory->expects($this->once())
            ->method("UserPdo")
            ->with($user, null, true)
            ->will($this->returnValue($user_pdo_model));
        $this->factory->expects($this->never())
            ->method("User");

        // Update the user
        $modified_user = $this->object->updateUser($user);
    }

    /**
     * @covers CougarTutorial\User::updateUser
     * @expectedException \Cougar\Exceptions\AccessDeniedException
     */
    public function testUpdateUserWithIdentity()
    {
        // Create the user object we have allegedly modified
        $user = new UserModel;
        $user->givenName = "John";
        $user->lastName = "Doe";
        $user->emailAddress = "john.doe@example.com";
        $user->password = "Password";

        // Create a new identity with the information we want
        $identity = new Identity("nobody@example.com", array("admin" => false));

        // Add our test authentication provider
        $identity_provider = new UserTestIdentity($identity);
        $this->security->addAuthenticationProvider($identity_provider);
        $this->security->authenticate();

        // Create the mock objects for the model and model factory
        $user_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\UserPdo")
            ->disableOriginalConstructor()
            ->getMock();
        $user_pdo_model->expects($this->never())
            ->method("save");

        $this->factory->expects($this->once())
            ->method("UserPdo")
            ->with($user, null, true)
            ->will($this->returnValue($user_pdo_model));
        $this->factory->expects($this->never())
            ->method("User");

        // Update the user
        $modified_user = $this->object->updateUser($user);
    }

    /**
     * @covers CougarTutorial\User::updateUser
     */
    public function testUpdateUserWithUserIdentity()
    {
        // Create a new identity with the information we want
        $identity = new Identity("john.doe@example.com",
            array("admin" => false));

        // Add our test authentication provider
        $identity_provider = new UserTestIdentity($identity);
        $this->security->addAuthenticationProvider($identity_provider);
        $this->security->authenticate();

        // Create the user object we have allegedly modified
        $user = new UserModel;
        $user->givenName = "John";
        $user->lastName = "Doe";
        $user->emailAddress = "john.doe@example.com";
        $user->password = "Password";

        // Create the mock objects for the model and model factory
        $user_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\UserPdo")
            ->disableOriginalConstructor()
            ->getMock();
        $user_pdo_model->expects($this->once())
            ->method("__get")
            ->with("id")
            ->will($this->returnValue($user->id));
        $user_pdo_model->expects($this->once())
            ->method("save");

        $this->factory->expects($this->once())
            ->method("UserPdo")
            ->with($user, null, true)
            ->will($this->returnValue($user_pdo_model));
        $this->factory->expects($this->once())
            ->method("User")
            ->with($user_pdo_model, null, true)
            ->will($this->returnValue($user));

        // Update the user
        $modified_user = $this->object->updateUser($user);
        $this->assertEquals($user, $modified_user);
    }

    /**
     * @covers CougarTutorial\User::updateUser
     */
    public function testUpdateUserWithAdminIdentity()
    {
        // Create a new identity with the information we want
        $identity = new Identity("admin@example.com", array("admin" => true));

        // Add our test authentication provider
        $identity_provider = new UserTestIdentity($identity);
        $this->security->addAuthenticationProvider($identity_provider);
        $this->security->authenticate();

        // Create the user object we have allegedly modified
        $user = new UserModel;
        $user->givenName = "John";
        $user->lastName = "Doe";
        $user->emailAddress = "john.doe@example.com";
        $user->password = "Password";

        // Create the mock objects for the model and model factory
        $user_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\UserPdo")
            ->disableOriginalConstructor()
            ->getMock();
        $user_pdo_model->expects($this->once())
            ->method("save");

        $this->factory->expects($this->once())
            ->method("UserPdo")
            ->with($user, null, true)
            ->will($this->returnValue($user_pdo_model));
        $this->factory->expects($this->once())
            ->method("User")
            ->with($user_pdo_model, null, true)
            ->will($this->returnValue($user));

        // Update the user
        $modified_user = $this->object->updateUser($user);
        $this->assertEquals($user, $modified_user);
    }

    /**
     * @covers CougarTutorial\User::deleteUser
     * @expectedException \Cougar\Exceptions\AccessDeniedException
     */
    public function testDeleteUser()
    {
        // Create the mock objects for the model and model factory
        $user_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\UserPdo")
            ->disableOriginalConstructor()
            ->getMock();
        $user_pdo_model->expects($this->never())
            ->method("delete");

        $this->factory->expects($this->once())
            ->method("UserPdo")
            ->with(array("id" => "john.doe@example.com"), null, true)
            ->will($this->returnValue($user_pdo_model));
        $this->factory->expects($this->never())
            ->method("User");

        // Delete the user
        $this->object->deleteUser("john.doe@example.com");
    }

    /**
     * @covers CougarTutorial\User::deleteUser
     * @expectedException \Cougar\Exceptions\AccessDeniedException
     */
    public function testDeleteUserWithIdentity()
    {
        // Create a new identity with the information we want
        $identity = new Identity("nobody@example.com", array("admin" => false));

        // Add our test authentication provider
        $identity_provider = new UserTestIdentity($identity);
        $this->security->addAuthenticationProvider($identity_provider);
        $this->security->authenticate();

        // Create the mock objects for the model and model factory
        $user_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\UserPdo")
            ->disableOriginalConstructor()
            ->getMock();
        $user_pdo_model->expects($this->never())
            ->method("delete");

        $this->factory->expects($this->once())
            ->method("UserPdo")
            ->with(array("id" => "john.doe@example.com"), null, true)
            ->will($this->returnValue($user_pdo_model));
        $this->factory->expects($this->never())
            ->method("User");

        // Delete the user
        $this->object->deleteUser("john.doe@example.com");
    }

    /**
     * @covers CougarTutorial\User::deleteUser
     */
    public function testDeleteUserWithUserIdentity()
    {
        // Create a new identity with the information we want
        $identity = new Identity("john.doe@example.com",
            array("admin" => false));

        // Add our test authentication provider
        $identity_provider = new UserTestIdentity($identity);
        $this->security->addAuthenticationProvider($identity_provider);
        $this->security->authenticate();

        // Create the user object we will return
        $user = new UserModel;
        $user->givenName = "John";
        $user->lastName = "Doe";
        $user->emailAddress = "john.doe@example.com";
        $user->password = "Password";

        // Create the mock objects for the model and model factory
        $user_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\UserPdo")
            ->disableOriginalConstructor()
            ->getMock();
        $user_pdo_model->expects($this->once())
            ->method("__get")
            ->with("id")
            ->will($this->returnValue($user->id));
        $user_pdo_model->expects($this->once())
            ->method("delete");

        $this->factory->expects($this->once())
            ->method("UserPdo")
            ->with(array("id" => "john.doe@example.com"), null, true)
            ->will($this->returnValue($user_pdo_model));
        $this->factory->expects($this->never())
            ->method("User");

        // Delete the user
        $this->object->deleteUser("john.doe@example.com");
    }

    /**
     * @covers CougarTutorial\User::deleteUser
     */
    public function testDeleteUserWithAdminIdentity()
    {
        // Create a new identity with the information we want
        $identity = new Identity("admin@example.com", array("admin" => true));

        // Add our test authentication provider
        $identity_provider = new UserTestIdentity($identity);
        $this->security->addAuthenticationProvider($identity_provider);
        $this->security->authenticate();

        // Create the user object we will return
        $user = new UserModel;
        $user->givenName = "John";
        $user->lastName = "Doe";
        $user->emailAddress = "john.doe@example.com";
        $user->password = "Password";

        // Create the mock objects for the model and model factory
        $user_pdo_model = $this->getMockBuilder(
            "\\CougarTutorial\\Models\\UserPdo")
            ->disableOriginalConstructor()
            ->getMock();
        $user_pdo_model->expects($this->once())
            ->method("delete");

        $this->factory->expects($this->once())
            ->method("UserPdo")
            ->with(array("id" => "john.doe@example.com"), null, true)
            ->will($this->returnValue($user_pdo_model));
        $this->factory->expects($this->never())
            ->method("User");

        // Delete the user
        $this->object->deleteUser("john.doe@example.com");
    }
}

class UserTestIdentity implements iAuthenticationProvider
{
    public function __construct($identity)
    {
        if (! class_exists("PHPUnit_Framework_TestCase", false))
        {
            throw new Exception(
                "A Test Identity can only be used in a unit test");
        }

        $this->identity = $identity;
    }

    public function authenticate()
    {
        return $this->identity;
    }

    protected $identity;
}
?>
