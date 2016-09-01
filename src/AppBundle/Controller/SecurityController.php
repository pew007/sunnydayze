<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        if ($this->get('security.authorization_checker')
                 ->isGranted('IS_AUTHENTICATED_FULLY')
        ) {
            return $this->redirectToRoute('authenticated');
        }
        $authUtils    = $this->get('security.authentication_utils');
        $lastUsername = $authUtils->getLastUsername();

        return $this->render(':security:login.html.twig', ['lastUsername' => $lastUsername]);
    }

    /**
     * @Route("/authenticated", name="authenticated")
     */
    public function authenticated()
    {
        $user = $this->getCurrentUser();
        $user->updateLastLogin();

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('admin_dashboard');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        $this->get('security.token_storage')
             ->setToken(null);
        $this->get('session')
             ->invalidate();

        $this->redirectToRoute('login');
    }

    private function getEntityManager()
    {
        return $this->getDoctrine()
                    ->getManager();
    }

    /**
     * @return User
     */
    private function getCurrentUser()
    {
        return $this->get('security.token_storage')
                    ->getToken()
                    ->getUser();
    }
}