<?php

namespace App\Security;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiTokenAuthenticator extends AbstractGuardAuthenticator
{
    protected JWTEncoderInterface $encoder;
    protected UserRepository $userRepository;

    public function __construct(JWTEncoderInterface $encoder, UserRepository $userRepository)
    {
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
    }

    public function supports(Request $request)
    {
        return $request->headers->has('Authorization');
    }

    public function getCredentials(Request $request)
    {
        $extractor = new AuthorizationHeaderTokenExtractor(
            'Bearer',
            'Authorization'
        );

        $token = $extractor->extract($request);

        if (!$token) {
            return;
        }

        return $token;
    }

    /**
     * @return UserInterface|void|null
     *
     * @throws JWTDecodeFailureException
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $data = $this->encoder->decode($credentials);

        if (false === $data) {
            throw new CustomUserMessageAuthenticationException('Invalid Token');
        }

        $email = $data['email'];

        return $this->userRepository->findOneBy(['email' => $email]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // TODO: Implement onAuthenticationSuccess() method.
    }

    public function supportsRememberMe()
    {
        return false;
    }

    public function start(Request $request, ?AuthenticationException $authException = null)
    {
        // TODO: Implement start() method.
    }
}
