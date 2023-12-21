<?php

namespace App\Controller;

use App\Entity\Chercheur;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface; // Import EntityManagerInterface

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        // Use the injected $session object to set the attribute
        $session->set('currentUserId', 57);

        $chercheur = Chercheur::findChercheurById($entityManager, 57);
        if ($chercheur) {
            $session->set('currentUserNom', $chercheur->getNom());
            $session->set('currentUserPrenom', $chercheur->getPrenom());
            $session->set('currentUserEmail', $chercheur->getEmail());
            $session->set('currentUserRole', $chercheur->getRole());
        }
        
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }
}

