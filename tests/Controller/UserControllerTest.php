<?php

namespace App\Tests\Controller;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testListUsersUnauthorize()
    {
        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['id' => 1]);
        $client->loginUser($user, 'main');

        $client->request('GET', '/admin/users');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testListUsersAdmin()
    {
        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['id' => 4]);
        $client->loginUser($user, 'main');

        $client->request('GET', '/admin/users');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/users/create');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'newUser',
            'user[password][first]' => 'new123456',
            'user[password][second]' => 'new123456',
            'user[email]' => 'newEmail@email.com'
        ]);
        $client->submit($form);

        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertSame('/', $client->getRequest()->getPathInfo());
    }

    public function testEditUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/users/1/edit');
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'user',
            '_password' => '123456'
        ]);
        $client->submit($form);

        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['id' => 1]);
        $client->loginUser($user, 'main');

        $crawler = $client->request('GET', '/users/1/edit');

        $form = $crawler->selectButton('Modifier')->form([
            'user[username]' => 'editUser',
            'user[password][first]' => 'editPassword',
            'user[password][second]' => 'editPassword',
            'user[email]' => 'editEmail@email.com'
        ]);

        $client->submit($form);

        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertSame('/', $client->getRequest()->getPathInfo());
    }
}
