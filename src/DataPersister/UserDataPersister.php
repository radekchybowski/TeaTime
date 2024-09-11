<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\User;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

final class UserDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private ContextAwareDataPersisterInterface $decorated,
        private UserPasswordHasherInterface $userPasswordHasher,
        private UserAuthenticatorInterface $userAuthenticator,
        private LoginFormAuthenticator $authenticator,
    ) {
    }

    public function supports($data, array $context = []): bool
    {
        return $this->decorated->supports($data, $context);
    }

    public function persist($data, array $context = [])
    {
        if ($data instanceof User && ($context['collection_operation_name'] ?? null) === 'post') {
            $user = $this->register($data);
            $result = $this->decorated->persist($user, $context);
        } elseif ($data instanceof User && ($context['item_operation_name'] ?? null) === 'put') {
            if (!str_contains($data->getPassword(), '$2y$13$')) {
                $data->setPassword(
                    $this->userPasswordHasher->hashPassword(
                        $data,
                        $data->getPassword()
                    )
                );
            }
            $result = $this->decorated->persist($data, $context);
        } else {
            $result = $this->decorated->persist($data, $context);
        }

        return $result;
    }

    public function remove($data, array $context = [])
    {
        return $this->decorated->remove($data, $context);
    }

    private function register(User $data)
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $user->setEmail($data->getEmail());

        /* @var string $name */
        $name = $data->getName();
        $surname = $data->getSurname();

        if (null !== $name) {
            $user->setName($name);
        }
        if (null !== $surname) {
            $user->setSurname($surname);
        }

        // encode the plain password
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $data->getPassword()
            )
        );

        //        $this->userAuthenticator->authenticateUser(
        //            $user,
        //            $this->authenticator,
        //            $request
        //        );

        return $user;
    }
}
