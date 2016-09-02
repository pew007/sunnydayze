<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminProductController extends Controller
{
    /**
     * @Route("/admin/products", name="admin_products")
     */
    public function indexAction()
    {
        $repo = $this->get('doctrine.entity_manager')
                     ->getRepository('AppBundle:Product');
        $products = $repo->findAll();

        return $this->render(':admin/products:index.html.twig', ['products' => $products]);
    }

    /**
     * @Route("/admin/products/new", name="new_admin_product")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $this->get('doctrine.entity_manager')
                 ->save($product);

            return $this->redirectToRoute('admin_products');
        }

        return $this->render(
            ':admin/products:new.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}
