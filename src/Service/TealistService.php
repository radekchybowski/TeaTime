<?php
/**
 * Tealist service.
 */

namespace App\Service;

use App\Entity\Tealist;
use App\Entity\User;
use App\Repository\TealistRepository;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class TealistService.
 */
class TealistService implements TealistServiceInterface
{
    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Tealist repository.
     */
    private TealistRepository $tealistRepository;

    /**
     * Constructor.
     *
     * @param PaginatorInterface   $paginator            Paginator
     * @param TealistRepository $tealistRepository Tealist repository
     */
    public function __construct(PaginatorInterface $paginator, TealistRepository $tealistRepository)
    {
        $this->paginator = $paginator;
        $this->tealistRepository = $tealistRepository;
    }

    /**
     * Get paginated list.
     *
     * @param int                $page    Page number
     * @param User               $author  Tealists author
     * @param array<string, int> $filters Filters array
     *
     * @return PaginationInterface<SlidingPagination> Paginated list
     *
     * @throws NonUniqueResultException
     */
    public function getPaginatedList(int $page, User $author, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);
        if (in_array('ROLE_ADMIN', $author->getRoles())
        ) {
            $pagination = $this->paginator->paginate(
                $this->tealistRepository->queryAll($filters),
                $page,
                TealistRepository::PAGINATOR_ITEMS_PER_PAGE
            );
        } else {
            $pagination = $this->paginator->paginate(
                $this->tealistRepository->queryByAuthor($author, $filters),
                $page,
                TealistRepository::PAGINATOR_ITEMS_PER_PAGE
            );
        }

        return $pagination;
    }

    /**
     * Find by id.
     *
     * @param int $id Tealist id
     *
     * @return Tealist|null Tealist entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Tealist
    {
        return $this->tealistRepository->findOneById($id);
    }

    /**
     * Save entity.
     *
     * @param Tealist $tealist Tealist entity
     */
    public function save(Tealist $tealist): void
    {
        $this->tealistRepository->save($tealist);
    }

    /**
     * Delete entity.
     *
     * @param Tealist $tealist Tealist entity
     */
    public function delete(Tealist $tealist): void
    {
        $this->tealistRepository->delete($tealist);
    }

    /**
     * Delete all entities where param user = author.
     *
     * @param User $user User entity
     */
    public function deleteTealistByAuthor(User $user): void
    {
        /** @var Tealist[] $tealistsArray Array of tealists to delete */
        $tealistsArray = $this->tealistRepository->findByAuthor($user);

        foreach ($tealistsArray as $tealist) {
            $this->tealistRepository->delete($tealist);
        }
    }

    /**
     * Prepare filters for the tealists list.
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
//        if (!empty($filters['category_id'])) {
//            $category = $this->categoryService->findOneById($filters['category_id']);
//            if (null !== $category) {
//                $resultFilters['category'] = $category;
//            }
//        }
//
//        if (!empty($filters['tag_id'])) {
//            $tag = $this->tagService->findOneById($filters['tag_id']);
//            if (null !== $tag) {
//                $resultFilters['tag'] = $tag;
//            }
//        }

        return $resultFilters;
    }
}
