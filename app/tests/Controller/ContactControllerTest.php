<?php
/**
 * Contact Controller Test.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ContactControllerTest.
 */
class ContactControllerTest extends WebTestCase
{
    /**
     * Test Contact Route.
     */
    public function testControllerRoute(): void
    {
        // given

        $client = static::createClient();

        // when

        $client->request('GET', '/contact');
        $responseCode = $client->getResponse()->getStatusCode();

        // then

        $this->assertEquals(302, $responseCode);
    }
}
