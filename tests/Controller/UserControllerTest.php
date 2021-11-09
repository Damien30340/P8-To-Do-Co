<?php

namespace App\Tests\Controller;


use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    use ProvidePathsTrait;
    use NeedLoginTrait;

    public function testAccessListUsersUnauthorize()
    {
        $client = self::createClient();
        self::loginUser($client);

        $client->request('GET', '/admin/users');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    /**
     * @param string $path
     * @dataProvider provideUserPaths
     */
    public function testAccessAnonymeUnauthorize(string $path)
    {
        $client = self::createClient();
        $client->request('GET', $path);
        $this->assertResponseStatusCodeSame(302);
    }

    public function testListUsersAdmin()
    {
        $client = self::createClient();
        self::loginAdmin($client);

        $client->request('GET', '/admin/users');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateUser()
    {
        $client = self::createClient();
        $client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(200);

        $client->submitForm('Ajouter', [
            'user[username]' => 'newUser',
            'user[password][first]' => 'new123456',
            'user[password][second]' => 'new123456',
            'user[email]' => 'newEmail@email.com'
        ]);

        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();

        $this->assertRouteSame('homepage');
    }

    public function testEditUser()
    {
        $client = self::createClient();
        self::loginUser($client);
        $client->request('GET', '/users/1/edit');

        $client->submitForm('Modifier', [
            'user[username]' => 'editUser',
            'user[password][first]' => 'editPassword',
            'user[password][second]' => 'editPassword',
            'user[email]' => 'editEmail@email.com'
        ]);

        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertRouteSame('homepage');
    }

    public function testViewTasksUser(){
        $client = self::createClient();
        $user = self::session($client);

        $this->assertInstanceOf(Task::class, $user->getTasks()->first());
    }

    public function testDeleteTasksUser(){
        $client = self::createClient();
        $user = self::session($client);

        $count = count($user->getTasks());
        $user->removeTask($user->getTasks()->first());
        $this->assertNotEquals($count, $user->getTasks()->count());
    }

    public function testAddTasksUser(){
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
