<?php

namespace App\Tests\SmokeTest\ApplicationAvailabilityFunctionalTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url, $expectedStatus = 200)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertResponseStatusCodeSame($expectedStatus);
    }

    public function urlProvider()
    {
        yield "homepage" => ['/', 302];
        yield "admin_list_users" => ['/admin/users', 302];
        yield "user_create" => ['/users/create', 200];
        yield "user_edit" => ['/users/1/edit', 302];
        yield "tasks_list" => ['/tasks', 302];
        yield "tasks_edit" => ['/tasks/1/edit', 302];
        yield "tasks_delete" => ['/tasks/1/delete', 302];
        yield "tasks_toggle" => ['/tasks/1/toggle', 302];
        yield "app_login" => ['/login', 200];
        yield "app_logout" => ['/logout', 302];
    }
}