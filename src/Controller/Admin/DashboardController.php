<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Entity\Product;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private OrderRepository $orderRepository,
        private ProductRepository $productRepository
    ){}

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
//        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
//        return $this->redirect($adminUrlGenerator->setController(ProductCrudController::class)->generateUrl());
//

        $totalProducts = $this->productRepository->count([]);
        $totalOrders = $this->orderRepository->count([]);
        $revenue = $this->orderRepository->getTotalRevenue();

        // Récupérer les commandes par mois pour le graphique
        $ordersByMonth = $this->orderRepository->countOrdersByMonth();
        $labels = array_keys($ordersByMonth);
        $data = array_values($ordersByMonth);

        return $this->render('admin/dashboard.html.twig', [
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'revenue' => $revenue,
            'labels' => json_encode(array_column($ordersByMonth, 'month')),  // Récupère bien les mois
            'data' => json_encode(array_column($ordersByMonth, 'count')),  // Récupère bien les comptes
        ]);



    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Breizhsport Admin');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::linkToCrud('Products', 'fas fa-box', Product::class),
            MenuItem::linkToCrud('Orders', 'fas fa-shopping-cart', Order::class),
        ];
    }

}
