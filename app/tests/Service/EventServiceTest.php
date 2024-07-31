<?php
/**
 * Category service tests.
 */

namespace App\Tests\Service;

use App\Entity\Category;
use App\Entity\Event;
use App\Service\EventService;
use App\Service\EventServiceInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CategoryServiceTest.
 */
class EventServiceTest extends KernelTestCase
{
    /**
     * Category repository.
     */
    private ?EntityManagerInterface $entityManager;

    /**
     * Category service.
     */
    private ?EventServiceInterface $eventService;

    /**
     * Set up test.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function setUp(): void
    {
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->eventService = $container->get(EventService::class);
    }

    /**
     * Test save.
     *s
     * @throws ORMExceptions
     */
    public function testSave(): void
    {
        // given
        $expectedEvent = new Event();
        $expectedEvent->setName('Test Event');

        // when
        $this->eventService->save($expectedEvent);

        // then
        $expectedEventId = $expectedEvent->getId();
        $resultCategory = $this->entityManager->createQueryBuilder()
            ->select('event')
            ->from(Event::class, 'event')
            ->where('category.id = :id')
            ->setParameter(':id', $expectedEventId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedEvent, $resultCategory);
    }

    /**
     * Test delete.
     *
     * @throws OptimisticLockException|ORMException
     */
    public function testDelete(): void
    {
        // given
        $eventToDelete = new Event();
        $eventToDelete->setName('Test Event');
        $this->entityManager->persist($eventToDelete);
        $this->entityManager->flush();
        $deletedEventId = $eventToDelete->getId();

        // when
        $this->eventService->delete($eventToDelete);

        // then
        $resultEvent = $this->entityManager->createQueryBuilder()
            ->select('event')
            ->from(Event::class, 'event')
            ->where('event.id = :id')
            ->setParameter(':id', $deletedEventId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($resultEvent);
    }

    /**
     * Test find by id.
     *
     * @throws ORMException
     */
    public function testFindById(): void
    {
        // given
        $expectedCategory = new Category();
        $expectedCategory->setName('Test Category');
        $this->entityManager->persist($expectedCategory);
        $this->entityManager->flush();
        $expectedCategoryId = $expectedCategory->getId();

        // when
        $resultCategory = $this->categoryService->findOneById($expectedCategoryId);

        // then
        $this->assertEquals($expectedCategory, $resultCategory);
    }

    /**
     * Test pagination empty list.
     */
    public function testGetPaginatedList(): void
    {
        // given
        $page = 1;
        $dataSetSize = 3;
        $expectedResultSize = 3;

        $counter = 0;
        while ($counter < $dataSetSize) {
            $category = new Category();
            $category->setName('Test Category #'.$counter);
            $this->categoryService->save($category);

            ++$counter;
        }

        // when
        $result = $this->categoryService->getPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }
}

