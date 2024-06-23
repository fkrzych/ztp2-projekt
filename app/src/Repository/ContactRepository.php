<?php
/**
 * Contact repository.
 */

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Contact;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class ContactRepository.
 *
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @extends ServiceEntityRepository<Contact>
 */
class ContactRepository extends ServiceEntityRepository
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
        parent::__construct($registry, Contact::class);
    }

    /**
     * Query contacts by author.
     *
     * @param User                  $user    User entity
     * @param array<string, object> $filters Filters
     *
     * @return QueryBuilder Query builder
     */
    public function queryByAuthor(User $user, array $filters = []): QueryBuilder
    {
        $queryBuilder = $this->queryAll($filters);

        $queryBuilder->andWhere('contact.author = :author')
            ->setParameter('author', $user);

        return $queryBuilder;
    }

    /**
     * Query all records.
     *
     * @param array $filters Filters
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(array $filters): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select('contact')
            ->orderBy('contact.name', 'DESC');

        return $this->applyFiltersToList($queryBuilder, $filters);
    }

    /**
     * Query for searching.
     *
     * @param User   $user    User entity
     * @param string $pattern Pattern for searching
     *
     * @return QueryBuilder Query builder
     */
    public function querySearch(User $user, string $pattern): QueryBuilder
    {
        $pattern = '%'.$pattern.'%';

        return $this->getOrCreateQueryBuilder()
            ->select('contact')
            ->orderBy('contact.name', 'DESC')
            ->where('contact.name like :pattern')
            ->setParameter(':pattern', $pattern)
            ->andWhere('contact.author = :author')
            ->setParameter(':author', $user);
    }

    /**
     * Save entity.
     *
     * @param Category $category Category entity
     */
    public function save(Contact $contact): void
    {
        $this->getEntityManager()->persist($contact);
        $this->getEntityManager()->flush();
    }

    /**
     * Delete entity.
     *
     * @param Category $category Category entity
     */
    public function delete(Contact $contact): void
    {
        $this->getEntityManager()->remove($contact);
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

        return $queryBuilder;
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('contact');
    }
}
