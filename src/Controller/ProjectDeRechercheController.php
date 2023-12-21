<?php

namespace App\Controller;

use App\Entity\ProjectDeRecherche;
use App\Form\ProjectDeRechercheType;
use App\Repository\ProjectDeRechercheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/project_de_recherche')]
class ProjectDeRechercheController extends AbstractController
{
    #[Route('/', name: 'app_project_de_recherche_index', methods: ['GET'])]
    public function index(ProjectDeRechercheRepository $projectDeRechercheRepository): Response
    {
        return $this->render('project_de_recherche/index.html.twig', [
            'project_de_recherches' => $projectDeRechercheRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_project_de_recherche_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $projectDeRecherche = new ProjectDeRecherche();
        $form = $this->createForm(ProjectDeRechercheType::class, $projectDeRecherche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($projectDeRecherche);
            $entityManager->flush();

            return $this->redirectToRoute('app_project_de_recherche_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project_de_recherche/new.html.twig', [
            'project_de_recherche' => $projectDeRecherche,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_project_de_recherche_show', methods: ['GET'])]
    public function show(ProjectDeRecherche $projectDeRecherche): Response
    {
        return $this->render('project_de_recherche/show.html.twig', [
            'project_de_recherche' => $projectDeRecherche,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_project_de_recherche_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProjectDeRecherche $projectDeRecherche, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectDeRechercheType::class, $projectDeRecherche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_project_de_recherche_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project_de_recherche/edit.html.twig', [
            'project_de_recherche' => $projectDeRecherche,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_project_de_recherche_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectDeRecherche $projectDeRecherche, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projectDeRecherche->getId(), $request->request->get('_token'))) {
            $entityManager->remove($projectDeRecherche);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_project_de_recherche_index', [], Response::HTTP_SEE_OTHER);
    }
}