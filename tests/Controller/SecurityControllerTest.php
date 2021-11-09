<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin(){
        $client = static::createClient();    
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();

        $client->submitForm('Se connecter', [
            '_username' => 'user1',
            '_password' => '123456'
        ]);

        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertRouteSame('homepage');
    }

    public function testLoginInvalidCredentials()
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();

        $client->submitForm('Se connecter', [
            '_username' => 'fakeUser',
            '_password' => 'fakePassword'
        ]);

        $this->assertResponseStatusCodeSame(302);
        $crawler = $client->followRedirect();
        $this->assertSame(
            "Invalid credentials.",
            $crawler->filter('.alert.alert-danger')->text()
        );
    }
}
