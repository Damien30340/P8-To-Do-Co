<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait NeedLoginTrait
{
    private static function session(KernelBrowser $client): User
    {
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['id' => 1]);
        $client->loginUser($user, 'main');

        return $user;
    }

    private static function hydrateUser(KernelBrowser $client): User
    {
        $userRepository = $client->getContainer()->get(UserRepository::class);

        return $userRepository->findOneBy(['id' => 1]);
    }

    private static function hydrateAdmin(KernelBrowser $client): User
    {
        $userRepository = $client->getContainer()->get(UserRepository::class);

        return $userRepository->findOneBy(['id' => 4]);
    }
}
