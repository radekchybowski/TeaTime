<?php
/**
 * Rating controller.
 */

namespace App\Controller;

use App\Entity\Rating;
use App\Service\RatingServiceInterface;
use App\Form\Type\RatingAdminType;
use Form\Type\RatingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class RatingController.
 */
#[Route('/rating')]
class RatingController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param RatingServiceInterface $ratingService Rating service
     * @param TranslatorInterface    $translator    Translator
     */
    public function __construct(private RatingServiceInterface $ratingService, private TranslatorInterface $translator)
    {
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(name: 'rating_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        $pagination = $this->ratingService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render('rating/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Rating $rating Rating
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'rating_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(Rating $rating): Response
    {
        return $this->render('rating/show.html.twig', ['record' => $rating]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create',
        name: 'rating_create',
        methods: 'GET|POST',
    )]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request): Response
    {
        $rating = new Rating();
        $form = $this->createForm(RatingAdminType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->ratingService->save($rating);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('rating_index');
        }

        return $this->render(
            'rating/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Rating  $rating  Rating entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'rating_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Rating $rating): Response
    {
        $form = $this->createForm(
            RatingAdminType::class,
            $rating,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('rating_edit', ['id' => $rating->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->ratingService->save($rating);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('rating_index');
        }

        return $this->render(
            'rating/edit.html.twig',
            [
                'form' => $form->createView(),
                'rating' => $rating,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Rating  $rating  Rating entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'rating_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Rating $rating): Response
    {
        $form = $this->createForm(
            FormType::class,
            $rating,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('rating_delete', ['id' => $rating->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->ratingService->delete($rating);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('rating_index');
        }

        return $this->render(
            'rating/delete.html.twig',
            [
                'form' => $form->createView(),
                'rating' => $rating,
            ]
        );
    }
}
