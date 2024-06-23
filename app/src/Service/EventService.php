<?php
/**
 * Event service.
 */

namespace App\Service;

use App\Entity\Event;
use App\Entity\User;
use App\Repository\EventRepository;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class EventService.
 */
class EventService implements EventServiceInterface
{
    public $tagService;
    /**
     * Constructor.
     *
     * @param CategoryServiceInterface $categoryService Category service
     * @param PaginatorInterface       $paginator       Paginator
     * @param TagServiceInterface      $tagService      Tag service
     * @param EventRepository          $eventRepository Event repository
     */
    public function __construct(private readonly CategoryServiceInterface $categoryService, private readonly PaginatorInterface $paginator, private readonly EventRepository $eventRepository)
    {
    }

    /**
     * Get paginated list.
     *
     * @param int                $page    Page number
     * @param User               $author  Author
     * @param array<string, int> $filters Filters array
     *
     * @return PaginationInterface<string, mixed> Paginated list
     *
     * @throws NonUniqueResultException
     */
    public function getPaginatedList(int $page, User $author, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->eventRepository->queryByAuthor($author, $filters),
            $page,
            EventRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Get paginated list for search.
     *
     * @param int    $page    Page number
     * @param User   $author  Author
     * @param string $pattern Pattern
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedListSearch(int $page, User $author, string $pattern): PaginationInterface
    {
        $pattern = $this->preparePattern($pattern);

        return $this->paginator->paginate(
            $this->eventRepository->querySearch($author, $pattern),
            $page,
            EventRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Event $event Event entity
     */
    public function save(Event $event): void
    {
        $this->eventRepository->save($event);
    }

    /**
     * Delete entity.
     *
     * @param Event $event Event entity
     */
    public function delete(Event $event): void
    {
        $this->eventRepository->delete($event);
    }

    /**
     * Prepare filters for the tasks list.
     *
     * @param array<string, int> $filters Raw filters from request
     *
     * @return array<string, object> Result array of filters
     *
     * @throws NonUniqueResultException
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];

        if (!empty($filters['category_id'])) {
            $category = $this->categoryService->findOneById($filters['category_id']);
            if ($category instanceof \App\Entity\Category) {
                $resultFilters['category'] = $category;
            }
        }

        if (!empty($filters['tag_id'])) {
            $tag = $this->tagService->findOneById($filters['tag_id']);
            if ($tag instanceof \App\Entity\Tag) {
                $resultFilters['tag'] = $tag;
            }
        }

        return $resultFilters;
    }

    /**
     * Prepare pattern.
     *
     * @param string $pattern Pattern for searching
     *
     * @return string Result pattern
     */
    private function preparePattern(string $pattern): string
    {
        return $pattern;
    }
}
