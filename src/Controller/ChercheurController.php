<?php

namespace App\Controller;

use App\Entity\Chercheur;
use App\Form\ChercheurType;
use App\Repository\ChercheurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/chercheur')]
class ChercheurController extends AbstractController
{
    #[Route('/', name: 'app_chercheur_index', methods: ['GET'])]
    public function index(ChercheurRepository $chercheurRepository): Response
    {
        return $this->render('chercheur/index.html.twig', [
            'chercheurs' => $chercheurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_chercheur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chercheur = new Chercheur();
        $form = $this->createForm(ChercheurType::class, $chercheur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chercheur);
            $entityManager->flush();

            return $this->redirectToRoute('app_chercheur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chercheur/new.html.twig', [
            'chercheur' => $chercheur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chercheur_show', methods: ['GET'])]
    public function show(Chercheur $chercheur): Response
    {
        return $this->render('chercheur/show.html.twig', [
            'chercheur' => $chercheur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chercheur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chercheur $chercheur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChercheurType::class, $chercheur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chercheur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chercheur/edit.html.twig', [
            'chercheur' => $chercheur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chercheur_delete', methods: ['POST'])]
    public function delete(Request $request, Chercheur $chercheur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chercheur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($chercheur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chercheur_index', [], Response::HTTP_SEE_OTHER);
    }
}
