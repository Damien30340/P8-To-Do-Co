<?php

namespace App\Tests\Controller;


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

    public function testListTask()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'user',
            '_password' => '123456'
        ]);
        $client->submit($form);
        $crawler = $client->followRedirect();

        $button = $crawler->selectLink('Consulter la liste des tâches à faire');
        $client->click($button->link());

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testActionCreateTaskByHomePage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'user',
            '_password' => '123456'
        ]);
        $client->submit($form);
        $crawler = $client->followRedirect();

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
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'user',
            '_password' => '123456'
        ]);
        $client->submit($form);
        $crawler = $client->followRedirect();

        $button = $crawler->selectLink('Consulter la liste des tâches à faire');
        $client->click($button->link());

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $crawler = $client->getCrawler();

        $button = $crawler->selectLink('Créer une tâche');
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

    public function testActionEditTask()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'user',
            '_password' => '123456'
        ]);
        $client->submit($form);

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

    public function testActionRemoveTask()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'user',
            '_password' => '123456'
        ]);
        $client->submit($form);

        $client->request('GET', '/tasks/1/delete');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertSame('/tasks', $client->getRequest()->getPathInfo());
    }

    public function testActionToggleTask()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'user',
            '_password' => '123456'
        ]);
        $client->submit($form);

        $client->request('GET', '/tasks/1/toggle');

        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertSame('/tasks', $client->getRequest()->getPathInfo());
    }
}
