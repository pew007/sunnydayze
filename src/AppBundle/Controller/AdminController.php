<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/dashboard", name="admin_dashboard")
     */
    public function indexAction()
    {
        $user = $this->getCurrentUser();
        $user->updateLastLogin();

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        return $this->render(':admin:index.html.twig');
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
