<?php
/**
 * Main Controller Test.
 */

namespace App\Tests\Controller;

use App\Controller\MainController;
use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\EventRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ContactControllerTest.
 */
class MainControllerTest extends WebTestCase
{
    /**
     * Test Main Route.
     */
    public function testMainRoute(): void
    {
        // given

        $client = static::createClient();

        // when

        $client->request('GET', '/main');
        $responseCode = $client->getResponse()->getStatusCode();

        // then

        $this->assertEquals(302, $responseCode);
    }
}

