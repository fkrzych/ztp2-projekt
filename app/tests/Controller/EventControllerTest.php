<?php
/**
 * EventController Test.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class EventControllerTest.
 */
class EventControllerTest extends WebTestCase
{
    /**
     * Test Event Route.
     */
    public function testControllerRoute(): void
    {
        // given

        $client = static::createClient();

        // when

        $client->request('GET', '/event');
        $responseCode = $client->getResponse()->getStatusCode();

        // then

        $this->assertEquals(302, $responseCode);
    }
}

