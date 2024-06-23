<?php
/**
 * ContactController Test.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ContactControllerTest.
 */
class CategoryControllerTest extends WebTestCase
{
    /**
     * Test Controller Route.
     */
    public function testCategoryRoute(): void
    {
        // given

        $client = static::createClient();

        // when

        $client->request('GET', '/category');
        $responseCode = $client->getResponse()->getStatusCode();

        // then

        $this->assertEquals(302, $responseCode);
    }
}
