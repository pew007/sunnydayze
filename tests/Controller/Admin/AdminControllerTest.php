<?php

namespace AppBundle\Tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/admin/dashboard');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $this->assertContains("Redirecting to <a href=\"/login\">/login</a>",
                              $client->getResponse()
                                     ->getContent()
        );
    }
}
