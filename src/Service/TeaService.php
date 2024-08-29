<?php
/**
 * Tea service.
 */

namespace App\Service;

use App\Entity\Tea;
use App\Entity\User;
use App\Repository\TeaRepository;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class TeaService.
 */
class TeaService implements TeaServiceInterface
{
    /**
     * Constructor.
     *
     * @param CategoryServiceInterface $categoryService Category service
     * @param PaginatorInterface       $paginator       Paginator
     * @param TagServiceInterface      $tagService      Tag service
     * @param TeaRepository            $teaRepository   Tea repository
     */
    public function __construct(
        private CategoryServiceInterface $categoryService,
        private PaginatorInterface $paginator,
        private TagServiceInterface $tagService,
        private RatingServiceInterface $ratingService,
        private CommentServiceInterface $commentService,
        private TeaRepository $teaRepository)
    {}

    /**
     * Get paginated list.
     *
     * @param int                $page    Page number
     * @param User               $author  Teas author
     * @param array<string, int> $filters Filters array
     *
     * @return PaginationInterface<SlidingPagination> Paginated list
     *
     * @throws NonUniqueResultException
     */
    public function getPaginatedList(int $page, User $author, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);
        if (in_array('ROLE_USER', $author->getRoles())
        ) {
            $pagination = $this->paginator->paginate(
                $this->teaRepository->queryAll($filters),
                $page,
                TeaRepository::PAGINATOR_ITEMS_PER_PAGE
            );
        } else {
            $pagination = $this->paginator->paginate(
                $this->teaRepository->queryByAuthor($author, $filters),
                $page,
                TeaRepository::PAGINATOR_ITEMS_PER_PAGE
            );
        }

        return $pagination;
    }

    /**
     * @return Tea[]
     */
    public function getAll(): array
    {
        return $this->teaRepository->findAll();
    }

    /**
     * Find by id.
     *
     * @param int $id Tea id
     *
     * @return Tea|null Tea entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Tea
    {
        return $this->teaRepository->findOneById($id);
    }

    /**
     * Save entity.
     *
     * @param Tea $tea Tea entity
     */
    public function save(Tea $tea): void
    {
        $this->teaRepository->save($tea);
    }

    /**
     * Delete entity.
     *
     * @param Tea $tea Tea entity
     */
    public function delete(Tea $tea): void
    {
        $this->teaRepository->delete($tea);
    }

    /**
     * Delete all entities where param user = author.
     *
     * @param User $user User entity
     */
    public function deleteTeaByAuthor(User $user): void
    {
        /** @var Tea[] $teasArray Array of teas to delete */
        $teasArray = $this->teaRepository->findByAuthor($user);

        foreach ($teasArray as $tea) {
            $this->commentService->deleteCommentsByTea($tea);
            $this->teaRepository->delete($tea);
        }
    }

    /**
     * Prepare filters for the teas list.
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
            if (null !== $category) {
                $resultFilters['category'] = $category;
            }
        }

        if (!empty($filters['tag_id'])) {
            $tag = $this->tagService->findOneById($filters['tag_id']);
            if (null !== $tag) {
                $resultFilters['tag'] = $tag;
            }
        }

        if (!empty($filters['tealist_id'])) {
            $tealist = $this->tealistService->findOneById($filters['tealist_id']);
            if (null !== $tealist) {
                $resultFilters['tealist'] = $tealist;
            }
        }

        if (!empty($filters['tea_name'])) {
            $resultFilters['tea_name'] = $filters['tea_name'];
        }

        return $resultFilters;
    }
}
