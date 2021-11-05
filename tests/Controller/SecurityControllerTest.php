<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin(){
        $client = static::createClient();    
        $crawler = $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'user',
            '_password' => '123456'
        ]);

        $client->submit($form);
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testLoginInvalidCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'fakeUsername',
            '_password' => 'fakePassword'
        ]);
        $client->submit($form);

        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();

        $this->assertSame(
            "Invalid credentials.",
            $crawler->filter('.alert.alert-danger')->text()
        );
    }

    //TODO Voir avec Thomas
    public function testLogout()
    {
        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['id' => 1]);
        $client->loginUser($user, 'main');

//        $crawler = $client->request('GET', '/');

//        $logout = $crawler->filter('a:contains("Se déconnecter")')->filter('.pull-right.btn.btn-danger');
//        $client->click($logout->link());

//        $crawler = $client->getCrawler();
//        //We are testing the logout url and redirection after
//        $this->assertSame('/logout', $crawler->getUri());
        $client->request('GET', '/logout');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }
}
