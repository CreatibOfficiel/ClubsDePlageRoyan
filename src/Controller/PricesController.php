<?php

namespace App\Controller;

use App\DTO\CardDto;
use App\Entity\Order;
use App\Entity\User;
use App\Form\CardType;
use App\Repository\OrderRepository;
use App\Repository\SwimmingPackBalanceRepository;
use App\Repository\SwimmingPackRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PricesController extends AbstractController
{
    #[Route('/prices', name: 'app_prices')]
    public function index(SwimmingPackRepository $swimmingPackRepository): Response
    {
        $swimmingPacks = $swimmingPackRepository->findAll();

        return $this->render('prices/prices.html.twig', [
            'swimming_packs' => $swimmingPacks,
        ]);
    }

    #[Route('/buy/{id}', name: 'swimming_pack_buy')]
    public function buy(Request $request, SwimmingPackRepository $swimmingPackRepository, SwimmingPackBalanceRepository $swimmingPackBalanceRepository, OrderRepository $orderRepository, UserRepository $userRepository, $id): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $swimmingPack = $swimmingPackRepository->find($id);
        if (!$swimmingPack) {
            return $this->redirectToRoute('app_home');
        }

        $cardDto = new CardDto();
        $form = $this->createForm(CardType::class, $cardDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $swimmingPackBalance = $user->getSwimmingPackBalance();
            $swimmingPackBalance->addSwimmingPack($swimmingPack);

            $order = new Order();
            $order->setUser($user);
            $order->setSwimmingPack($swimmingPack);
            $order->setStatus('VALIDATED');

            // take last 4 numbers of card
            $checkDigit = substr($cardDto->cardNumber, -4);
            $order->setCheckDigit($checkDigit);

            $orderRepository->save($order);
            $swimmingPackBalanceRepository->save($swimmingPackBalance);
            $userRepository->save($user, true);

            $this->addFlash('success', 'Vous avez acheté un pack de ' . $swimmingPack->getLessonsAmount() . ' leçons !');

            return $this->redirectToRoute('app_booking_lesson');
        }

        return $this->render('prices/buy.html.twig', [
            'cardForm' => $form->createView(),
            'swimming_pack' => $swimmingPack,
            'user' => $user,
        ]);
    }
}
