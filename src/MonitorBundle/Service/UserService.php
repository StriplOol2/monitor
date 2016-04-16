<?php

namespace MonitorBundle\Service;

use FOS\UserBundle\Model\UserInterface;
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
        /** @var UserInterface $user */
        $user = $this->userRepository->findOneBy(['username' => $login]);
        //@todo add password
        return $user;
    }
}
