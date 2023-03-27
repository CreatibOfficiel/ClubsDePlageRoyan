<?php

namespace App\Controller\Admin;

use App\Entity\SwimmingPack;
use App\Form\SwimmingPackType;
use App\Repository\SwimmingPackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/swimming_pack')]
class AdminSwimmingPackController extends AbstractController
{
    #[Route('/', name: 'app_admin_swimming_pack_index', methods: ['GET'])]
    public function index(SwimmingPackRepository $swimmingPackRepository): Response
    {
        return $this->render('admin/swimming_pack/index.html.twig', [
            'swimming_packs' => $swimmingPackRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_swimming_pack_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SwimmingPackRepository $swimmingPackRepository): Response
    {
        $swimmingPack = new SwimmingPack();
        $form = $this->createForm(SwimmingPackType::class, $swimmingPack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $swimmingPackRepository->save($swimmingPack, true);

            return $this->redirectToRoute('app_admin_swimming_pack_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/swimming_pack/new.html.twig', [
            'swimming_pack' => $swimmingPack,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_swimming_pack_show', methods: ['GET'])]
    public function show(SwimmingPack $swimmingPack): Response
    {
        return $this->render('admin/swimming_pack/show.html.twig', [
            'swimming_pack' => $swimmingPack,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_swimming_pack_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SwimmingPack $swimmingPack, SwimmingPackRepository $swimmingPackRepository): Response
    {
        $form = $this->createForm(SwimmingPackType::class, $swimmingPack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $swimmingPackRepository->save($swimmingPack, true);

            return $this->redirectToRoute('app_admin_swimming_pack_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/swimming_pack/edit.html.twig', [
            'swimming_pack' => $swimmingPack,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_swimming_pack_delete', methods: ['POST'])]
    public function delete(Request $request, SwimmingPack $swimmingPack, SwimmingPackRepository $swimmingPackRepository): Response
    {
        var_dump($request->request->get('_token'));
        if ($this->isCsrfTokenValid('delete'.$swimmingPack->getId(), $request->request->get('_token'))) {
            $swimmingPackRepository->remove($swimmingPack, true);
        }

        return $this->redirectToRoute('app_admin_swimming_pack_index', [], Response::HTTP_SEE_OTHER);
    }
}
