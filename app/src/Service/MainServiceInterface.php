<?php
/**
 * Main service interface.
 */

namespace App\Service;

use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface MainServiceInterface.
 */
interface MainServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int  $page   Page number
     * @param User $author User
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page, User $author): PaginationInterface;

    /**
     * If currents exists.
     *
     * @param User $author Author
     *
     * @return int Paginated list
     */
    public function ifCurrentsExist(User $author): int;
}
