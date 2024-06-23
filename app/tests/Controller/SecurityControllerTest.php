<?php
/**
 * Security Controller Test.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SecurityControllerTest.
 */
class SecurityControllerTest extends WebTestCase
{
    /**
     * Test Login Route.
     */
    public function testLoginRoute(): void
    {
        // given

        $client = static::createClient();

        // when

        $client->request('GET', '/login');
        $responseCode = $client->getResponse()->getStatusCode();

        // then

        $this->assertEquals(200, $responseCode);
    }

    /**
     * Test Login Route.
     */
    public function testLogoutRoute(): void
    {
        // given

        $client = static::createClient();

        // when

        $client->request('GET', '/logout');
        $responseCode = $client->getResponse()->getStatusCode();

        // then

        $this->assertEquals(302, $responseCode);
    }
}

