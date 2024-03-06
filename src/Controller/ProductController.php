<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Panier;
use App\Entity\PanierItem;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/product')]
class ProductController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/all', name: 'product_all', methods: ['GET'])]
    public function all(): JsonResponse
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        // Serialize products to JSON format
        $serializedProducts = [];
        foreach ($products as $product) {
            $serializedProducts[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'datefabrication' => $product->getDatefabrication(),
                'quantity' => $product->getQuantite(),
                'image' => $product->getImage(),
                'likes' => $product->getLikes(),
                // Add more fields as needed
            ];
        }

        // Return JSON response
        return new JsonResponse($serializedProducts);
    }


    #[Route('/shop', name: 'shop', methods: ['GET'])]
    public function shop(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager->getRepository(Product::class)->findAll();

        // Serialize products to JSON format
        $serializedProducts = [];
        foreach ($products as $product) {
            $serializedProducts[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'datefabrication' => $product->getDatefabrication(),
                'quantity' => $product->getQuantite(),
                'image' => $product->getImage(),
                'likes' => $product->getLikes(),
                // Add more fields as needed
            ];
        }

        return $this->render('product/shop.html.twig', [
            'products' => $serializedProducts,
        ]);
    }


    #[Route('/cart', name: 'cart', methods: ['GET'])]
    public function cart(EntityManagerInterface $entityManager): Response
    {

        // Serialize products to JSON format

        return $this->render('product/cart.html.twig');
    }



    #[Route('/new', name: 'product_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($product);
            $this->entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'product_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'product_delete', methods: ['POST'])]
    public function delete(Request $request, int $id): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return $this->redirectToRoute('product_index');
    }
    #[Route('/routes', name: 'routes')]

    public function myAction(): Response
    {
        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', 'http://127.0.0.1:5500');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');

        // Your controller logic

        return $response;
    }

    #[Route('/{id}/likes', name: 'product_likes', methods: ['POST'])]
    public function likeProduct(Request $request, $id): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        // Increment likes
        $likes = $product->getLikes() + 1;
        $product->setLikes($likes);

        // Save changes
        // Replace this with your preferred method of persisting changes to the database
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return $this->render('product/shop.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/products/sort', name: 'sort_products', methods: ['GET'])]
    public function sortProduct(Request $request): Response
    {
        try {
            $sortBy = $request->query->get('sort', 'likes'); // Default sort by likes
            $products = $this->entityManager->getRepository(Product::class)->findBy([], [$sortBy => 'DESC']);
            $data = [];
            foreach ($products as $product) {
                $data[] = [
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'price' => $product->getPrice(),
                    'datefabrication' => $product->getDatefabrication(),
                    'quantity' => $product->getQuantite(),
                    'image' => $product->getImage(),
                    'likes' => $product->getLikes(),
                ];
            }

            return $this->render('product/shop.html.twig', [
                'products' => $data,
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function sortProducts(Request $request): JsonResponse
    {
        try {
            $sortBy = $request->query->get('sort', 'likes'); // Default sort by likes
            $products = $this->entityManager->getRepository(Product::class)->findBy([], [$sortBy => 'DESC']);
            $data = [];
            foreach ($products as $product) {
                $data[] = [
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'price' => $product->getPrice(),
                    'datefabrication' => $product->getDatefabrication(),
                    'quantity' => $product->getQuantite(),
                    'image' => $product->getImage(),
                    'likes' => $product->getLikes(),
                ];
            }

            return new JsonResponse($data);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[Route('/add-to-cart/{productId}', name: 'add_to_cart')]
    public function addToCart(int $productId): RedirectResponse
    {
        // Retrieve the product entity using the provided ID
        $product = $this->entityManager->getRepository(Product::class)->find($productId);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        // Create a new PanierItem entity and associate it with the retrieved product
        $panierItem = new PanierItem();
        $panierItem->setProduct($product);
        $panierItem->setQuantity(1); // You can set the quantity as needed

        // Retrieve the Panier (cart) entity
        $panier = $this->entityManager->getRepository(Panier::class)->findOneBy([]);

        // If no cart exists, create a new one
        if (!$panier) {
            $panier = new Panier();
            $this->entityManager->persist($panier);
        }

        // Add the newly created PanierItem to the Panier (cart)
        $panier->addPanierItem($panierItem);

        // Save changes to the database
        $this->entityManager->flush();

        // Redirect back to the shop page or any other page as needed
        return $this->redirectToRoute('shop');
    }



    #[Route('/', name: 'product_index', methods: ['GET'])]
    public function index(): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }
}
