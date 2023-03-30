<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/orders')]
class AdminOrdersController extends AbstractController
{
    #[Route('/', name: 'app_admin_orders_index', methods: ['GET'])]
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->render('admin/orders/index.html.twig', [
            'orders' => $orderRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_orders_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Order $order, OrderRepository $orderRepository): Response
    {
        // get status form field from request
        $status = $request->get('status');

        if ($status == '0') {
            $status = 'PENDING';
        } elseif ($status == '1') {
            $status = 'CANCELLED';
        } elseif ($status == '2') {
            $status = 'VALIDATED';
        } else {
            throw new \Exception('Invalid status');
        }

        // if status is not invalid, update order status
        $order->setStatus($status);
        $orderRepository->save($order, true);

        return $this->redirectToRoute('app_admin_orders_index', [], Response::HTTP_SEE_OTHER);
    }
}
