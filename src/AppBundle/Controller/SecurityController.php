<?php

namespace AppBundle\Controller;

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
            return $this->redirectToRoute('admin_dashboard');
        }
        $authUtils    = $this->get('security.authentication_utils');
        $lastUsername = $authUtils->getLastUsername();

        return $this->render(':security:login.html.twig', ['lastUsername' => $lastUsername]);
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
}