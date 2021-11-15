<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @codeCoverageIgnore
 */
class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 3; ++$i) {
            $user = (new User())
                ->setUsername('user'.$i)
                ->setEmail('user'.$i.'@test.fr');
            $pass = $this->hasher->hashPassword($user, '123456');
            $user->setPassword($pass);
            $manager->persist($user);

            $this->addReference('user_'.$i, $user);
        }
        $manager->flush();

        $user = (new User())
            ->setUsername('adminUser')
            ->setEmail('adminUser@test.fr')
            ->setRoles(['ROLE_ADMIN']);
        $pass = $this->hasher->hashPassword($user, '123456');
        $user->setPassword($pass);
        $manager->persist($user);

        $manager->flush();
    }
}
