<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;

trait NeedLoginTrait
{
    private static function session($client){
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['id' => 1]);
        $client->loginUser($user, 'main');

        return $user;
    }

    private static function loginUser($client){
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['id' => 1]);
        $client->loginUser($user, 'main');
    }

    private static function loginAdmin($client){
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['id' => 4]);
        $client->loginUser($user, 'main');
    }
}