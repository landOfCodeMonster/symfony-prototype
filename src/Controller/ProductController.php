<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $products = $repository->findAllGranterThanPrice(10);

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/product/create", name="create_product")
     */
    public function createProduct(ValidatorInterface $validator): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $product = new Product();
        $product->setName('keyboard');
        $product->setPrice(109);
        $product->setDescription('Ergonomic and stylish!');

        // Validation
        $errors = $validator->validate($product);
        if(count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        // Persist product
        $entityManager->persist($product);

        // insert query
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }

    /**
     * @param int $id
     * @param ProductRepository $productRepository
     * @return Response
     * @Route("/product/{id}", name="product_show")
     */
    public function show(Product $product, ProductRepository $productRepository) : Response
    {
//        $product = $productRepository->find($id);

        if(!$product) {
            throw $this->createNotFoundException('No product find with id '. $product->getName());
        }

        return new Response('The name of the product is '. $product->getName());
    }

    /**
     * @Route("/product/edit/{id}", name="product_edit")
     */
    public function update(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if(!$product)
        {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $product->setName("The millionaire next door");
        $product->setPrice(30);
        $product->setDescription("The habit of rich people");
        $entityManager->flush();

        return $this->redirectToRoute('product_show', [
            'id' => $product->getId()
        ]);
    }

    /**
     * @Route("/product/delete/{id}", name="product_delete")
     */
    public function delete(Product $product): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute("product_show", [
            'id' => $product->getId()
        ]);
    }
}
