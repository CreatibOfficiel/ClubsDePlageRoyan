<?php

namespace App\Controller\Admin;

use App\Entity\Club;
use App\Form\ClubType;
use App\Repository\ClubRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/clubs')]
class AdminClubsController extends AbstractController
{
    #[Route('/', name: 'app_admin_clubs_index', methods: ['GET'])]
    public function index(ClubRepository $clubRepository): Response
    {
        return $this->render('admin/clubs/index.html.twig', [
            'clubs' => $clubRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_clubs_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClubRepository $clubRepository): Response
    {
        $club = new Club();
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clubRepository->save($club, true);

            return $this->redirectToRoute('app_admin_clubs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/clubs/new.html.twig', [
            'club' => $club,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_clubs_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Club $club, ClubRepository $clubRepository): Response
    {
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clubRepository->save($club, true);

            return $this->redirectToRoute('app_admin_clubs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/clubs/edit.html.twig', [
            'club' => $club,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_clubs_delete', methods: ['POST'])]
    public function delete(Request $request, Club $club, ClubRepository $clubRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$club->getId(), $request->request->get('_token'))) {
            $clubRepository->remove($club, true);
        }

        return $this->redirectToRoute('app_admin_clubs_index', [], Response::HTTP_SEE_OTHER);
    }
}
