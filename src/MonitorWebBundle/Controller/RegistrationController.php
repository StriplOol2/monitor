<?php

namespace MonitorWebBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as FosuRegistrationController;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use MonitorBundle\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class RegistrationController extends FosuRegistrationController
{
    /**
     * @Route("register/", name="monitor_web.user.register")
     * @param Request $request
     * @return null|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
            $this->setAuthKey($user);
            $user->addRole('ROLE_SIMPLE');
            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_registration_confirmed');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return $this->render('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param User|UserInterface $user
     */
    private function setAuthKey(UserInterface &$user)
    {
        $userRepository = $this->get('monitor.repository.user');
        $isUnique = false;
        $authKey = null;
        while (!$isUnique) {
            $authKey = md5($user->getUsernameCanonical());
            if (null === $userRepository->findOneBy(['authKey' => $authKey])) {
                $isUnique = true;
            }
        }
        $user->setAuthKey($authKey);
    }
}
