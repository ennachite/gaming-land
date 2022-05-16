<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
  /**
   * Home Page
   *
   * @param ProductRepository $productRepository
   * @return Response
   */
  #[Route('/', name: 'app_product')]
  public function index(ProductRepository $productRepository): Response
  {
    // $this->denyAccessUnlessGranted('ROLE_USER');
    $product = $productRepository->findBy([], ['createdAt' => 'DESC']);
    return $this->render('product/index.html.twig', ['products' => $product]);
  }

  /**
   * create product
   *
   * @param Request $request
   * @param EntityManagerInterface $em
   * @return Response
   */
  #[Route('/products/create', name: 'app_product_create', methods: 'GET|POST')]
  public function create(Request $request, EntityManagerInterface $em): Response
  {
    $this->denyAccessUnlessGranted('ROLE_USER');
    $product = new Product();
    $form = $this->createForm(ProductType::class, $product);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $product->setUserId($this->getUser());
      $em->persist($product);
      $em->flush();

      $this->addFlash('success', 'Product succefully created !');

      return $this->redirectToRoute('app_product');
    }

    return $this->render('product/create.html.twig', [
      'formProducts' => $form->createView()
    ]);
  }

  /**
   * Show Products
   *
   * @param Product $product
   * @return Response
   */
  #[Route('/products/{id<[0-9]+>}', name: 'products', methods: 'GET')]
  public function show(Product  $product): Response
  {
    $this->denyAccessUnlessGranted('ROLE_USER');
    return $this->render('product/show.html.twig', compact('product'));
  }

  /**
   * edit ptoducts
   *
   * @param Product $product
   * @param EntityManagerInterface $em
   * @param Request $request
   * @return Response
   */
  #[Route('/products/{id<[0-9]+>}/edit}', name: 'app_products_edit', methods: 'GET|POST')]
  public function edit(Product $product, EntityManagerInterface $em, Request $request): Response
  {
    $form = $this->createForm(ProductType::class, $product);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em->flush();
      $this->addFlash('success', 'Product succefully updated !');
      return $this->redirectToRoute('app_product');
    }

    return $this->render('product/edit.html.twig', [
      'product' => $product,
      'formEditProduct' => $form->createView()
    ]);
  }

  /**
   * delete Product
   *
   * @param Product $product
   * @param EntityManagerInterface $em
   * @param Request $request
   * @return Response
   */
  #[Route('/products/{id<[0-9]+>}', name: 'app_products_delete', methods: 'POST')]
  public function delete(Product $product, EntityManagerInterface $em, Request $request): Response
  {
    $em->remove($product);
    $em->flush();
    $this->addFlash('info', 'Product succefully deleted !');


    return $this->redirectToRoute('app_product');
  }
}
