<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminProductController
 * @package AppBundle\Controller\Admin
 * @Route("/admin")
 */
class AdminProductController extends Controller
{
    /**
     * @Route("/products", name="admin_products")
     */
    public function indexAction()
    {
        $repo     = $this->get('doctrine.entity_manager')
                         ->getRepository('AppBundle:Product');
        $products = $repo->findAll();

        return $this->render(':admin/products:index.html.twig', ['products' => $products]);
    }

    /**
     * @Route("/products/new", name="new_admin_product")
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

    /**
     * @Route("/product/{productId}", name="show_admin_product")
     * @param $productId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($productId)
    {
        $product = $this->getProductById($productId);

        return $this->render(
            ':admin/products:show.html.twig',
            [
                'product' => $product
            ]
        );
    }

    /**
     * @Route("/product/{productId}/update", name="edit_admin_product")
     * @param $productId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction($productId)
    {
        $product = $this->getProductById($productId);

        return $this->render(
            ':admin/products:edit.html.twig',
            [
                'product' => $product
            ]
        );
    }

    /**
     * @Route("/product/{productId}/delete", name="delete_admin_product")
     * @param $productId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($productId)
    {
        $product = $this->getProductById($productId);
        $this->get('doctrine.entity_manager')
             ->remove($product);

        return $this->redirectToRoute('admin_products');
    }

    private function getProductById($productId)
    {
        $repo = $this->get('doctrine.entity_manager')
                     ->getRepository('AppBundle:Product');

        $product = $repo->findOneBy(['id' => $productId]);

        if ($product) {
            return $product;
        }

        throw new EntityNotFoundException("Product does not exist.");
    }
}
