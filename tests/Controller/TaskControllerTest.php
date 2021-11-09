<?php

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    use ProvidePathsTrait;
    use NeedLoginTrait;
    /**
     * @param string $path
     * @dataProvider provideTaskPaths
     */
    public function testAccessTaskWithoutLogin(string $path)
    {
        $client = self::createClient();
        $client->request('GET', $path);
        $this->assertResponseStatusCodeSame(302);
    }

    public function testListTaskByHomePage()
    {
        $client = self::createClient();
        self::loginUser($client);

        $client->request('GET', '/');

        $client->clickLink('Consulter la liste des tâches à faire');

        $this->assertResponseStatusCodeSame(200);
    }

    public function testActionCreateTaskByHomePage()
    {
        $client = self::createClient();
        self::loginUser($client);

        $client->request('GET', '/');

        $client->clickLink('Créer une nouvelle tâche');

        $this->assertResponseStatusCodeSame(200);

        $client->submitForm('Ajouter', [
            'task[title]' => 'testTitle',
            'task[content]' => 'testContent'
        ]);

        $this->assertResponseStatusCodeSame(302);

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->selectLink('Créer une tâche')->count());
    }

    public function testActionCreateTaskByTasksPage()
    {
        $client = self::createClient();
        self::loginUser($client);

        $client->request('GET', '/tasks');

        $client->clickLink('Créer une tâche');

        $this->assertResponseStatusCodeSame(200);

        $client->submitForm('Ajouter', [
            'task[title]' => 'testTitle',
            'task[content]' => 'testContent'
        ]);

        $this->assertResponseStatusCodeSame(302);
    }

    public function testActionEditTask()
    {
        $client = self::createClient();
        self::loginUser($client);

        $client->request('GET', '/tasks/1/edit');
        $this->assertResponseStatusCodeSame(200);

        $client->submitForm('Modifier', [
            'task[title]' => 'testTitle',
            'task[content]' => 'testContent'
        ]);
        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();
        $this->assertRouteSame('task_list');
    }

    public function testActionRemoveTaskUnauthorize()
    {
        $client = self::createClient();
        self::loginUser($client);

        $client->request('GET', '/tasks/7/delete');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testActionRemoveTask()
    {
        $client = self::createClient();
        self::loginUser($client);

        $client->request('GET', '/tasks/1/delete');
        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();
        $this->assertRouteSame('task_list');
    }

    public function testActionRemoveTaskAnonyme()
    {
        $client = self::createClient();
        self::loginAdmin($client);

        $client->request('GET', '/tasks/8/delete');
        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertRouteSame('task_list');
    }

    public function testActionToggleTask()
    {
        $client = self::createClient();
        self::loginUser($client);

        $client->request('GET', '/tasks/1/toggle');
        $this->assertResponseStatusCodeSame(302);
        
        $client->followRedirect();
        $this->assertRouteSame('task_list');
    }
}
