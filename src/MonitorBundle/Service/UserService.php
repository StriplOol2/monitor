<?php

namespace MonitorBundle\Service;

use MonitorBundle\Entity\User;
use MonitorBundle\Repository\UserRepository;

class UserService
{
    /** @var UserRepository */
    protected $userRepository;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $login
     * @param string $password
     * @return null|User
     */
    public function auth($login, $password)
    {
        return $this->userRepository->findOneBy(['login' => $login, 'password' => $password]);
    }
}
