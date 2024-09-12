<?php
/**
 * Tea controller.
 */

namespace App\Controller;

use App\Entity\Rating;
use App\Entity\Tea;
use App\Entity\User;
use App\Form\Type\TeaType;
use App\Service\RatingServiceInterface;
use App\Service\TealistServiceInterface;
use App\Service\TeaServiceInterface;
use Form\Type\RatingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AboutController.
 */
#[Route('/about')]
class AboutController extends AbstractController
{
    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(name: 'about_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        return $this->render('about/index.html.twig');
    }
}
