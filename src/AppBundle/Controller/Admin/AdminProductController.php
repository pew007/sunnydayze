<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminProductController extends Controller
{
    /**
     * @Route("/admin/products", name="admin_products")
     */
    public function indexAction()
    {
        return $this->render(':admin/products:index.html.twig');
    }
}
