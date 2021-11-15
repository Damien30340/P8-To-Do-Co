<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    use ProvidePathsTrait;
    use NeedLoginTrait;

    private const MAIN_FIREWALL = 'main';

    public function testAccessListUsersUnauthorize(): void
    {
        $client = self::createClient();
        $client->loginUser($this->hydrateUser($client), self::MAIN_FIREWALL);

        $client->request('GET', '/admin/users');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider provideUserPaths
     */
    public function testAccessAnonymeUnauthorize(string $path): void
    {
        $client = self::createClient();
        $client->request('GET', $path);
        $this->assertResponseStatusCodeSame(302);
    }

    public function testListUsersAdmin(): void
    {
        $client = self::createClient();
        $client->loginUser($this->hydrateAdmin($client), self::MAIN_FIREWALL);

        $client->request('GET', '/admin/users');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateUser(): void
    {
        $client = self::createClient();
        $client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(200);

        $client->submitForm('Ajouter', [
            'user[username]' => 'newUser',
            'user[password][first]' => 'new123456',
            'user[password][second]' => 'new123456',
            'user[email]' => 'newEmail@email.com',
        ]);

        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();

        $this->assertRouteSame('homepage');
    }

    public function testEditUser(): void
    {
        $client = self::createClient();
        $client->loginUser($this->hydrateUser($client), self::MAIN_FIREWALL);
        $client->request('GET', '/users/1/edit');

        $client->submitForm('Modifier', [
            'user[username]' => 'editUser',
            'user[password][first]' => 'editPassword',
            'user[password][second]' => 'editPassword',
            'user[email]' => 'editEmail@email.com',
        ]);

        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertRouteSame('homepage');
    }

    public function testViewTasksUser(): void
    {
        $client = self::createClient();
        $user = self::session($client);

        $this->assertInstanceOf(Task::class, $user->getTasks()->first());
    }

    public function testDeleteTasksUser(): void
    {
        $client = self::createClient();
        $user = self::session($client);

        $count = count($user->getTasks());
        $user->removeTask($user->getTasks()->first());
        $this->assertNotEquals($count, $user->getTasks()->count());
    }

    public function testAddTasksUser(): void
    {
        $client = self::createClient();
        $user = self::session($client);

        $count = count($user->getTasks());

        $task = (new Task())
            ->setTitle('testTask')
            ->setContent('testContent');

        $user->addTask($task);

        $this->assertNotEquals($count, $user->getTasks()->count());
    }
}
