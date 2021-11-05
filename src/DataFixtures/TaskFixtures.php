<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $task = (new Task())
            ->setTitle('testTitle1')
            ->setContent('testContent1')
            ->setUser($this->getReference('user_1'));
        $manager->persist($task);
        $manager->flush();

        for($i = 2; $i <= 5; $i++){
            $rand = random_int(1, 3);

            $task = (new Task())
                ->setTitle('testTitle' .$i)
                ->setContent('testContent' .$i)
                ->setUser($this->getReference('user_' .$rand));
            $manager->persist($task);
        }
        $manager->flush();

        for($i = 6; $i <= 9; $i++){

        $task = (new Task())
            ->setTitle('testTitle' .$i)
            ->setContent('testContent' .$i);
        $manager->persist($task);
        }
        $manager->flush();
    }


    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
