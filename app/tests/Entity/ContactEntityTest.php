<?php
namespace App\Tests\Entity;

use App\Entity\Category;
use App\Entity\Contact;
use App\Entity\User;
use Monolog\Test\TestCase;

/**
 * Post class tests.
 */
class ContactEntityTest extends TestCase
{
    /**
     * Test can get and set data.
     */
    public function TestGetSetData(): void
    {
        $testedPost = new Contact();
        $testedPost->setName('Mariusz');
        $testedPost->setPhone('123456789');
        $testedPost->setAuthor(new User());

        self::assertSame('Mariusz', $testedPost->getName());
        self::assertSame('123456789', $testedPost->getPhone());
        self::assertSame($testedPost->getAuthor(), $testedPost->getAuthor());
    }
}