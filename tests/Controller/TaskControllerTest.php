<?php

namespace App\Tests\Controller;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testAccessTaskWithoutLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->request('GET', '/tasks/create');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->request('GET', '/tasks/1/edit');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->request('GET', '/tasks/1/toggle');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->request('GET', '/tasks/1/delete');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testListTaskByHomePage()
    {
        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['id' => 1]);
        $client->loginUser($user, 'main');

        $crawler = $client->request('GET', '/');

        $button = $crawler->selectLink('Consulter la liste des tâches à faire');
        $client->click($button->link());

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testActionCreateTaskByHomePage()
    {
        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['id' => 1]);
        $client->loginUser($user, 'main');

        $crawler = $client->request('GET', '/');

        $button = $crawler->selectLink('Créer une nouvelle tâche');
        $client->click($button->link());

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'testTitle',
            'task[content]' => 'testContent'
        ]);
        $client->submit($form);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->selectLink('Créer une tâche')->count());
    }

    public function testActionCreateTaskByTasksPage()
    {
        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['id' => 1]);
        $client->loginUser($user, 'main');

        $crawler = $client->request('GET', '/tasks');

        $button = $crawler->selectLink('Créer une tâche');
        $client->click($button->link());

        $crawler = $client->getCrawler();

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'testTitle',
            'task[content]' => 'testContent'
        ]);
        $client->submit($form);

        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testActionEditTask()
    {
        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['id' => 1]);
        $client->loginUser($user, 'main');

        $crawler = $client->request('GET', '/tasks/1/edit');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'testTitle',
            'task[content]' => 'testContent'
        ]);
        $client->submit($form);

        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();

        $this->assertSame('/tasks', $client->getRequest()->getPathInfo());
    }

    public function testActionRemoveTaskUnauthorize()
    {
        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['id' => 1]);
        $client->loginUser($user, 'main');

        $client->request('GET', '/tasks/7/delete');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testActionRemoveTask()
    {
        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['id' => 1]);
        $client->loginUser($user, 'main');

        $client->request('GET', '/tasks/1/delete');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertSame('/tasks', $client->getRequest()->getPathInfo());
    }

    public function testActionRemoveTaskAnonyme()
    {
        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['id' => 4]);
        $client->loginUser($user, 'main');

        $client->request('GET', '/tasks/8/delete');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertSame('/tasks', $client->getRequest()->getPathInfo());
    }

    public function testActionToggleTask()
    {
        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['id' => 1]);
        $client->loginUser($user, 'main');

        $client->request('GET', '/tasks/1/toggle');

        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertSame('/tasks', $client->getRequest()->getPathInfo());
    }
}
