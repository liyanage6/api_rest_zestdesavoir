<?php

namespace AppBundle\Security;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AuthTokenUserProvider implements UserProviderInterface
{
    protected $authTokenRepository;

    protected $userRepository;

    public function __construct (EntityRepository $authTokenRepository, EntityRepository $userRepository )
    {
        $this->userRepository = $userRepository;
        $this->authTokenRepository = $authTokenRepository;
    }

    public function getAuthToken ($authTokenHeader)
    {
        return $this->authTokenRepository->findOneBy(['value' => $authTokenHeader]);
    }

    public function loadUserByUsername ($email)
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }

    public function refreshUser (UserInterface $user)
    {
        // Le systéme d'authentification est stateless, on ne doit donc jamais appeler la méthode refreshUser
        throw new UnsupportedUserException();
    }

    public function supportsClass ($class)
    {
        return 'AppBundle\Entity\User' === $class;
    }

}