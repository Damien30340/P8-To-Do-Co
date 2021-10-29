<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    use HasErrorsTrait;

    /**
     * @return User
     */
    public function getEntity(){
        $user = new User();
        $user->setUsername('test');
        $user->setPassword('test');
        $user->setEmail('test@gmail.com');

        return $user;
    }

    public function testValidUserEntity()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidBlankUsername()
    {
        $user = new User();
        $user->setUsername('');
        $user->setEmail("test@test.com");
        $this->assertHasErrors($user, 1);
    }

    public function testInvalidFormatEmail()
    {
        $user = new User();
        $user->setUsername('Toto');
        $user->setEmail('toto');
        $this->assertHasErrors($user, 1);
    }


    public function testDefaultUserRole()
    {
        $this->assertSame(['ROLE_USER'], $this->getEntity()->getRoles());
    }

    public function testGetIdUser()
    {
        $user = new User();
        $this->assertEquals(null, $user->getId());
    }

}
