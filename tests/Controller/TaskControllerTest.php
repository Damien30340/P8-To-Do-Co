<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    use ProvidePathsTrait;
    use NeedLoginTrait;

    private const MAIN_FIREWALL = 'main';

    /**
     * @dataProvider provideTaskPaths
     */
    public function testAccessTaskWithoutLogin(string $path): void
    {
        $client = self::createClient();
        $client->request('GET', $path);
        $this->assertResponseStatusCodeSame(302);
    }

    public function testListTaskByHomePage(): void
    {
        $client = self::createClient();
        $client->loginUser(self::hydrateUser($client), self::MAIN_FIREWALL);
        $client->request('GET', '/');
        $client->clickLink('Consulter la liste des tâches à faire');

        $this->assertResponseStatusCodeSame(200);
    }

    public function testActionCreateTaskByHomePage(): void
    {
        $client = self::createClient();
        $client->loginUser(self::hydrateUser($client), self::MAIN_FIREWALL);

        $client->request('GET', '/');

        $client->clickLink('Créer une nouvelle tâche');

        $this->assertResponseStatusCodeSame(200);

        $client->submitForm('Ajouter', [
            'task[title]' => 'testTitle',
            'task[content]' => 'testContent',
        ]);

        $this->assertResponseStatusCodeSame(302);

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->selectLink('Créer une tâche')->count());
    }

    public function testActionCreateTaskByTasksPage(): void
    {
        $client = self::createClient();
        $client->loginUser(self::hydrateUser($client), self::MAIN_FIREWALL);

        $client->request('GET', '/tasks');

        $client->clickLink('Créer une tâche');

        $this->assertResponseStatusCodeSame(200);

        $client->submitForm('Ajouter', [
            'task[title]' => 'testTitle',
            'task[content]' => 'testContent',
        ]);

        $this->assertResponseStatusCodeSame(302);
    }

    public function testActionEditTask(): void
    {
        $client = self::createClient();
        $client->loginUser(self::hydrateUser($client), self::MAIN_FIREWALL);

        $client->request('GET', '/tasks/1/edit');
        $this->assertResponseStatusCodeSame(200);

        $client->submitForm('Modifier', [
            'task[title]' => 'testTitle',
            'task[content]' => 'testContent',
        ]);
        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();
        $this->assertRouteSame('task_list');
    }

    public function testActionRemoveTaskUnauthorize(): void
    {
        $client = self::createClient();
        $client->loginUser(self::hydrateUser($client), self::MAIN_FIREWALL);

        $client->request('GET', '/tasks/7/delete');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testActionRemoveTask(): void
    {
        $client = self::createClient();
        $client->loginUser(self::hydrateUser($client), self::MAIN_FIREWALL);

        $client->request('GET', '/tasks/1/delete');
        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();
        $this->assertRouteSame('task_list');
    }

    public function testActionRemoveTaskAnonyme(): void
    {
        $client = self::createClient();
        $client->loginUser(self::hydrateAdmin($client), self::MAIN_FIREWALL);

        $client->request('GET', '/tasks/8/delete');
        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertRouteSame('task_list');
    }

    public function testActionToggleTask(): void
    {
        $client = self::createClient();
        $client->loginUser(self::hydrateUser($client), self::MAIN_FIREWALL);

        $client->request('GET', '/tasks/1/toggle');
        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();
        $this->assertRouteSame('task_list');
    }
    
        public function testTaskDone(): void
    {
        $client = self::createClient();
        $client->loginUser(self::hydrateUser($client), self::MAIN_FIREWALL);

        $client->request('GET', '/tasks/done');
        $this->assertResponseStatusCodeSame(200);
        $this->assertRouteSame('task_done');
    }
}
