<?php
/**
 * Event repository.
 */

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class EventRepository.
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * Query events by author.
     *
     * @param User                  $user    User entity
     * @param array<string, object> $filters Filters
     *
     * @return QueryBuilder Query builder
     */
    public function queryByAuthor(User $user, array $filters = []): QueryBuilder
    {
        $queryBuilder = $this->queryAll($filters);

        $queryBuilder->andWhere('event.author = :author')
            ->setParameter('author', $user)
        ;

        return $queryBuilder;
    }

    /**
     * Query all records.
     *
     * @param array<string, object> $filters Filters
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(array $filters): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select('event', 'category')
            ->join('event.category', 'category')
            ->orderBy('event.date', 'DESC')
        ;

        return $this->applyFiltersToList($queryBuilder, $filters);
    }

    /**
     * Query events by author.
     *
     * @param User $user User entity
     *
     * @return QueryBuilder Query builder
     */
    public function queryByAuthorCurrent(User $user): QueryBuilder
    {
        $queryBuilder = $this->queryCurrent();

        $queryBuilder->andWhere('event.author = :author')
            ->setParameter('author', $user)
        ;

        return $queryBuilder;
    }

    /**
     * Query current events.
     *
     * @return QueryBuilder Query builder
     */
    public function queryCurrent(): QueryBuilder
    {
        $now = new \DateTime();
        $currentDate = $now->format('Y-m-d H:i:s');

        return $this->getOrCreateQueryBuilder()
            ->setParameter(':currentDate', $currentDate)
            ->select('event', 'category')
            ->where('event.date >= :currentDate')
            ->join('event.category', 'category')
            ->orderBy('event.date', 'ASC')
        ;
    }

    /**
     * Count current events.
     *
     * @param User $user User entity
     *
     * @return array Query builder
     */
    public function countCurrent(User $user): array
    {
        $now = new \DateTime();
        $currentDate = $now->format('Y-m-d H:i:s');

        return $this->getOrCreateQueryBuilder()
            ->setParameter(':currentDate', $currentDate)
            ->select('event', 'category')
            ->where('event.date >= :currentDate')
            ->join('event.category', 'category')
            ->andWhere('event.author = :author')
            ->setParameter('author', $user)
            ->getQuery()
            ->getScalarResult()
        ;
    }

    /**
     * Count events by category.
     *
     * @param Category $category Category
     *
     * @return int Number of events in category
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByCategory(Category $category): int
    {
        $qb = $this->getOrCreateQueryBuilder();

        return $qb->select($qb->expr()->countDistinct('event.id'))
            ->where('event.category = :category')
            ->setParameter(':category', $category)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Save entity.
     *
     * @param Event $event Event entity
     */
    public function save(Event $event): void
    {
        $this->getEntityManager()->persist($event);
        $this->getEntityManager()->flush();
    }

    /**
     * Delete entity.
     *
     * @param Event $event Event entity
     */
    public function delete(Event $event): void
    {
        $this->getEntityManager()->remove($event);
        $this->getEntityManager()->flush();
    }

    /**
     * Apply filters to paginated list.
     *
     * @param QueryBuilder          $queryBuilder Query builder
     * @param array<string, object> $filters      Filters array
     *
     * @return QueryBuilder Query builder
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, array $filters = []): QueryBuilder
    {
        if (isset($filters['category']) && $filters['category'] instanceof Category) {
            $queryBuilder->andWhere('category = :category')
                ->setParameter('category', $filters['category'])
            ;
        }

        return $queryBuilder;
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(?QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('event');
    }
}
