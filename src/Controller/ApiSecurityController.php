<?php
/**
 * Api Security Controller.
 */

namespace App\Controller;

use ApiPlatform\Core\Api\IriConverterInterface;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiSecurityController.
 */
#[Route(path: '/api')]
class ApiSecurityController extends AbstractController
{
    protected $iriConverter;
    protected $encoder;
    /**
     * ApiSecurityController constructor.
     */
    public function __construct(IriConverterInterface $iriConverter, JWTEncoderInterface $encoder)
    {
        $this->iriConverter = $iriConverter;
        $this->encoder = $encoder;
    }
    /**
     * Logging the user.
     *
     * @return Response HTTP Response
     *
     * @throws JWTEncodeFailureException
     */
    #[Route(path: '/login', name: 'api_login', methods: ['POST'])]
    public function login(): JsonResponse
    {
        $user = $this->getUser();
        assert($user instanceof User);

        if (!$this->getUser()) {
            throw $this->createNotFoundException();
        }

        $token = $this->encoder->encode([
            'email' => $user->getEmail(),
            'exp' => time() + 3600,
        ]);

        return new JsonResponse(['token' => $token, 'user' => $user], 201, [
            'Location' => $this->iriConverter->getIriFromItem($user),
        ]);
    }

    /**
     * Logging user out.
     */
    #[Route(path: '/logout', name: 'api_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
