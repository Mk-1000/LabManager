<?php

namespace App\Controller;

use App\Entity\Chercheur;
use App\Entity\Publication;
use App\Form\PublicationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PublicationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/publication')]
class PublicationController extends AbstractController
{
    #[Route('/', name: 'app_publication_index', methods: ['GET'])]
    public function index(Request $request, PublicationRepository $publicationRepository, SessionInterface $session): Response
    {
        $id = $session->get('currentUserId');

        if (!$id) {
            return $this->redirectToRoute('app_homepage');
        }

        $searchTerm = $request->query->get('search');
        $searchBy = $request->query->get('searchBy');

        $publications = [];

        if ($searchTerm && $searchBy) {
            // Use the custom method defined in the repository
            $publications = $publicationRepository->findByCustomSearch($searchTerm, $searchBy);
        } else {
            // If no search term or criteria, fetch all publications
            $publications = $publicationRepository->findAll();
        }

        return $this->render('publication/index.html.twig', [
            'publications' => $publications,
        ]);
    }

    #[Route('/new', name: 'app_publication_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();
        $currentUserId = $session->get('currentUserId');
        $chercheur = $entityManager->getRepository(Chercheur::class)->find($currentUserId); // Fetch Chercheur with $currentUserId

        // gets an attribute by name
        $currentUserId = $session->get('currentUserId');

        $publication = new Publication();
        // Create the form and pass the current user ID as an option
        $form = $this->createForm(PublicationType::class, $publication, [
            'current_user_id' => $currentUserId,
        ]);
        
        $publication->setAuteurs($chercheur->getPrenom() . ' ' . $chercheur->getNom());
        

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($publication);
            $entityManager->flush();

            return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publication/new.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publication_show', methods: ['GET'])]
    public function show(Publication $publication): Response
    {
        return $this->render('publication/show.html.twig', [
            'publication' => $publication,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_publication_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publication $publication, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        // gets an attribute by name
        $currentUserId = $session->get('currentUserId');

        // Create the form and pass the current user ID as an option
        $form = $this->createForm(PublicationType::class, $publication, [
            'current_user_id' => $currentUserId,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publication/edit.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publication_delete', methods: ['POST'])]
    public function delete(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
            $entityManager->remove($publication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
    }
}
