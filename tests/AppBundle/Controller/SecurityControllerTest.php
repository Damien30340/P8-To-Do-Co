<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'user',
            '_password' => '123456'
        ]);
        $client->submit($form);

        $crawler = $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $this->assertSame(
            "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !",
            $crawler->filter('h1')->text()
        );
    }

    public function testLoginInvalidCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'fakeUsername',
            '_password' => 'fakePassword'
        ]);
        $client->submit($form);

        $crawler = $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $this->assertSame(
            "Invalid credentials.",
            $crawler->filter('.alert.alert-danger')->text()
        );
    }

    public function testLogout()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'user',
            '_password' => '123456'
        ]);
        $client->submit($form);

        $this->assertSame('/login_check', $client->getRequest()->getRequestUri());

        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('.pull-right.btn.btn-danger')->count());

        $logout = $crawler->filter('a')->filter('.pull-right.btn.btn-danger');
        $client->click($logout->link());

        //We are testing the logout url and redirection after
        $this->assertSame('/logout', $client->getRequest()->getRequestUri());
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }
}
