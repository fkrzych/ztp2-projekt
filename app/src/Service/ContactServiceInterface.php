<?php
/**
 * Contact service interface.
 */

namespace App\Service;

use App\Dto\ContactListInputFiltersDto;
use App\Entity\Contact;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface ContactServiceInterface.
 */
interface ContactServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int   $page    Page number
     * @param User  $author  User
     * @param array $filters Filters
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page, User $author, ContactListInputFiltersDto $filters): PaginationInterface;

    /**
     * Get paginated list for search.
     *
     * @param int    $page    Page number
     * @param User   $author  Author
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
//    public function getPaginatedListSearch(int $page, User $author, ContactListInputFiltersDto $filters): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Contact $contact Contact entity
     */
    public function save(Contact $contact): void;

    /**
     * Delete entity.
     *
     * @param Contact $contact Contact entity
     */
    public function delete(Contact $contact): void;

    /**
     * Prepare pattern.
     *
     * @param string $pattern Pattern for searching
     *
     * @return string Result pattern
     */
    public function preparePattern(string $pattern): string;
}
