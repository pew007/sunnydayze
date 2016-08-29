<?php

namespace AppBundle\Controller;

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
        return $this->render(':admin:index.html.twig');
    }
}
