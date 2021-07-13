<?php

namespace App\Tests\Entity;

use App\Entity\Token;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TokenTest extends KernelTestCase
{

    public function getEntity(): Token
    {
        return new Token();
    }

    public function assertHasErrors(Token $token, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($token);
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

}
