<?php
namespace App\Tests\Entity;

use App\Entity\Category;
use PHPUnit\Framework\TestCase;

/**
 * Category class tests.
 */
class CategoryEntityTest extends TestCase
{
    /**
     * Test can get and set data.
     */
    public function testGetSetData(): void
    {
        $testedCategory = new Category();
        $testedCategory->setName('Testing Category');

        self::assertSame('Testing Category', $testedCategory->getName());
        self::assertSame($testedCategory->getId(), $testedCategory->getId());
    }
}