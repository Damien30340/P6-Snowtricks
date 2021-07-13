<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{

    public function getEntity(): User
    {
        return (new User())
            ->setUsername('user')
            ->setEmail('test@gmail.com')
            ->setPassword('123456789aA@')
            ->setConfirmPassword('123456789aA@');
    }

    public function assertHasErrors(User $user, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($user);
        $messages = [];
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    /**
     * Test Length
     */
    public function testLengthUsername()
    {
        $this->assertHasErrors($this->getEntity()->setUsername('usernameUsernameusere'), 1);
        $this->assertHasErrors($this->getEntity()->setUsername('us'), 1);
    }

    /**
     * Test Password
     */
    public function testBlankPassword()
    {
        $this->assertHasErrors($this->getEntity()->setPassword(''), 2);
    }
    public function testInvalidPassword()
    {
        $this->assertHasErrors($this->getEntity()->setPassword('15071990aA'), 2);
        $this->assertHasErrors($this->getEntity()->setPassword('aaaaaaaaaaaaa'), 2);
        $this->assertHasErrors($this->getEntity()->setPassword('AAAAAAAAAAAA@aaa'), 2);
    }

    /**
     * Test ConfirmPassword
     */
    public function testConfirmPassword()
    {
        $this->assertHasErrors($this->getEntity()->setConfirmPassword('123456789'), 1);
        $this->assertHasErrors($this->getEntity()->setConfirmPassword('1234@Aa'), 1);
    }

    /**
     * Test Email 
     */
    public function testInvalidMail()
    {
        $this->assertHasErrors($this->getEntity()->setEmail('test@test'), 1);
        $this->assertHasErrors($this->getEntity()->setEmail('testtest.com'), 1);
        $this->assertHasErrors($this->getEntity()->setEmail('testtest.fr'), 1);
        $this->assertHasErrors($this->getEntity()->setEmail('test @ test.com'), 1);
    }
}
