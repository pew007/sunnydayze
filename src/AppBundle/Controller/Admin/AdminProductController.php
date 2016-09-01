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
        $em = $this->getDoctrine()
                   ->getManager();
        $repo = $em->getRepository('AppBundle:Product');
        $products = $repo->findAll();

        return $this->render(':admin/products:index.html.twig', ['products' => $products]);
    }

    /**
     * @Route("/admin/products/new", name="new_admin_product")
     */
    public function addAction()
    {
        return $this->render(':admin/products:new.html.twig');
    }
}
