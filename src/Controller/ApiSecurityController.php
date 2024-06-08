<?php
/**
 * Api Security Controller.
 */

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

/**
 * Class ApiSecurityController.
 */
#[Route(path: '/api')]
class ApiSecurityController extends AbstractController
{
    /**
     * Logging the user.
     *
     * @return Response HTTP Response
     */
    #[Route(path: '/login', name: 'api_login', methods: ['POST'])]
    public function login(): Response
    {
        $user = $this->getUser();
        assert($user instanceof User);

        if(!$user) {
            return $this->json([
                'error' => 'Invalid login request: check that the Content-Type is set to application/json.'
            ], 401);
        }

        return $this->json(['user' => $user->getEmail()]);
    }

    /**
     * Logging user out.
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
