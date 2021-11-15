<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    use HasErrorsTrait;

    public function getEntity(): User
    {
        return (new User())
            ->setUsername('test')
            ->setPassword('test')
            ->setEmail('test@gmail.com');
    }

    public function testValidUserEntity(): void
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidBlankUsername(): void
    {
        $user = new User();
        $user->setUsername('');
        $user->setEmail('test@test.com');
        $this->assertHasErrors($user, 1);
    }

    public function testInvalidFormatEmail(): void
    {
        $user = new User();
        $user->setUsername('Toto');
        $user->setEmail('toto');
        $this->assertHasErrors($user, 1);
    }

    public function testDefaultUserRole(): void
    {
        $this->assertSame(['ROLE_USER'], $this->getEntity()->getRoles());
    }

    public function testGetIdUser(): void
    {
        $user = new User();
        $this->assertEquals(null, $user->getId());
    }
}
