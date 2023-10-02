<?php
/**
 * Tealist controller.
 */

namespace App\Controller;

use App\Entity\Tealist;
use App\Entity\User;
use App\Form\Type\TealistType;
use App\Service\TealistServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class TealistController.
 */
#[Route('/tealist')]
class TealistController extends AbstractController
{
    /**
     * Tealist service.
     */
    private TealistServiceInterface $tealistService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param TealistServiceInterface $tealistService Tealist service
     * @param TranslatorInterface     $translator     Translator
     */
    public function __construct(TealistServiceInterface $tealistService, TranslatorInterface $translator)
    {
        $this->tealistService = $tealistService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(name: 'tealist_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        $filters = $this->getFilters($request);
        /** @var User $user * */
        $user = $this->getUser();
        $pagination = $this->tealistService->getPaginatedList(
            $request->query->getInt('page', 1),
            $user,
            $filters
        );

        return $this->render('tealist/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Tealist $tealist Tealist entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}', name: 'tealist_show', requirements: ['id' => '[1-9]\d*'], methods: 'GET')]
    #[IsGranted('VIEW', subject: 'tealist')]
    public function show(Tealist $tealist): Response
    {
        return $this->render('tealist/show.html.twig', ['tealist' => $tealist]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'tealist_create', methods: 'GET|POST')]
    public function create(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $tealist = new Tealist();
        $tealist->setAuthor($user);
        $form = $this->createForm(
            TealistType::class,
            $tealist,
            ['action' => $this->generateUrl('tealist_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tealistService->save($tealist);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('tealist_index');
        }

        return $this->render(
            'tealist/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Tealist     $tealist     Tealist entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'tealist_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    #[IsGranted('EDIT', subject: 'tealist')]
    public function edit(Request $request, Tealist $tealist): Response
    {
        $form = $this->createForm(
            TealistType::class,
            $tealist,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('tealist_edit', ['id' => $tealist->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tealistService->save($tealist);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('tealist_index');
        }

        return $this->render(
            'tealist/edit.html.twig',
            [
                'form' => $form->createView(),
                'tealist' => $tealist,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Tealist     $tealist     Tealist entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'tealist_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    #[IsGranted('DELETE', subject: 'tealist')]
    public function delete(Request $request, Tealist $tealist): Response
    {
        $form = $this->createForm(
            FormType::class,
            $tealist,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('tealist_delete', ['id' => $tealist->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tealistService->delete($tealist);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('tealist_index');
        }

        return $this->render(
            'tealist/delete.html.twig',
            [
                'form' => $form->createView(),
                'tealist' => $tealist,
            ]
        );
    }

    /**
     * Get filters from request.
     *
     * @param Request $request HTTP request
     *
     * @return array<string, int> Array of filters
     *
     * @psalm-return array{category_id: int, tag_id: int, status_id: int}
     */
    private function getFilters(Request $request): array
    {
        $filters = [];
        $filters['tea_id'] = $request->query->getInt('filters_tea_id');

        return $filters;
    }
}
