<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class PanierController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    #[Route('/panier/add/{productId}', name: 'app_panier_add')]
    public function addToCart($productId,ProductRepository $repo)
   {

    $session = $this->requestStack->getSession();
    $product=$repo->find($productId);
    $cart = $session->get('cart', []);
    if (!isset($cart[$productId])) {
      $cart[$productId] = [
        'id'=>$productId,
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'image' => $product->getImage(),
            'quantity' => 0,
            'total'=>0
        ];
    }
    if(isset($_POST["quantite"]))
    {
    $quantite=intval($_POST["quantite"]);
    if($quantite==0)
        $quantite=1;
    }
    else
        $quantite=1;
 
    $cart[$productId]['quantity'] += $quantite;
    $cart[$productId]['total']=$quantite*$product->getPrice();

    $session->set('cart', $cart);

    return $this->redirectToRoute('app_panier');
   }
   #[Route('/panier', name: 'app_panier')]
   public function showCart(): Response
   {
       $session = $this->requestStack->getSession();
       $panier = $session->get('cart', []);
   
       return $this->render('product/cart.html.twig', [
           'panier' => $panier,
       ]);
   }
#[Route('/panier/delete/{id}', name: 'app_panier_delete')]
  public function removeFromCart($id)
{
    $session = $this->requestStack->getSession();
    $cart = $this->$session->get('cart', []);

    if (isset($cart[$id])) {
        unset($cart[$id]);
    }

    $this->$session->set('cart', $cart);

    return $this->redirectToRoute('app_panier');
}
}
