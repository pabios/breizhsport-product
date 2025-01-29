<?php

namespace App\Controller\Action;

use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/api/orders', name: 'create_order', methods: ['POST'])]
    public function createOrder(
        Request $request,
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        // ✅ Vérification des entrées
        if (!isset($data['customerEmail']) || !isset($data['orderDetails'])) {
            return new JsonResponse(['message' => 'Données invalides : email et orderDetails requis'], 400);
        }

        // ✅ Création de la commande
        $order = new Order();
        $order->setCustomerEmail($data['customerEmail']);

        $totalAmount = 0;
        $maxAllowedAmount = 99999999.99; // 🔥 Limite autorisée

        foreach ($data['orderDetails'] as $detail) {
            // 🔍 Vérifier si le produit existe
            $product = $productRepository->find($detail['productId']);
            if (!$product) {
                return new JsonResponse(["message" => "Produit introuvable: {$detail['productId']}"], 404);
            }

            $quantity = $detail['quantity'];
            $unitPrice = $product->getPrice();
            $subtotal = $unitPrice * $quantity;

            // 🚨 Vérifier que le sous-total est valide
            if ($subtotal > $maxAllowedAmount) {
                return new JsonResponse([
                    'message' => "Le prix total pour le produit {$product->getId()} dépasse la limite autorisée."
                ], 400);
            }

            // 🚨 Vérifier que le montant total ne dépasse pas la limite
            if (($totalAmount + $subtotal) > $maxAllowedAmount) {
                return new JsonResponse([
                    'message' => "Le montant total de la commande dépasse la limite autorisée."
                ], 400);
            }

            // ✅ Ajout du produit à la commande
            $orderDetail = new OrderDetail();
            $orderDetail->setProduct($product);
            $orderDetail->setQuantity($quantity);
            $orderDetail->setUnitPrice($unitPrice);

            $order->addOrderDetail($orderDetail);
            $entityManager->persist($orderDetail);

            $totalAmount += $subtotal;
        }

        // ✅ Arrondir le totalAmount pour éviter les erreurs de flottants
        $order->setTotalAmount(round($totalAmount, 2));

        // 🔄 Sauvegarde en base de données
        $entityManager->persist($order);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Commande created avec success',
            'orderId' => $order->getId(),
            'totalAmount' => $order->getTotalAmount()
        ], 201);
    }
}
