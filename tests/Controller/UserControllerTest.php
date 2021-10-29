<?php

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testListUsers()
    {
        $client = static::createClient();
        $client->request('GET', '/users');
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
        $this->assertSame('/users', $client->getRequest()->getPathInfo());
    }

    public function testEditUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/users/1/edit');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Modifier')->form([
            'user[username]' => 'editUser',
            'user[password][first]' => 'editPassword',
            'user[password][second]' => 'editPassword',
            'user[email]' => 'editEmail@email.com'
        ]);
        $client->submit($form);

        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertSame('/users', $client->getRequest()->getPathInfo());
    }


}
