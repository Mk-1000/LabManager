<?php

namespace App\Controller;

use App\Entity\Chercheur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class AuthManagerController extends AbstractController
{
    #[Route('/auth/manager', name: 'app_auth_manager')]
    public function index(): Response
    {
        return $this->render('auth_manager/index.html.twig', [
            'controller_name' => 'AuthManagerController',
        ]);
    }
    #[Route('/logout', name: 'app_logout')]

    public function logout(Request $request): RedirectResponse
    {
        // Clear the user's session
        $request->getSession()->invalidate();

        // Redirect to the home page or any desired URL after logout
        return new RedirectResponse($this->generateUrl('app_homepage'));
    }

    #[Route('/login', name: 'app_login')]

    public function login(Request $request,SessionInterface $session,EntityManagerInterface $entityManager): RedirectResponse
    {
        // Clear the user's session
        // Use the injected $session object to set the attribute
        $session->set('currentUserId', 72);

        $chercheur = Chercheur::findChercheurById($entityManager, 72);
        if ($chercheur) {
            $session->set('currentUserNom', $chercheur->getNom());
            $session->set('currentUserPrenom', $chercheur->getPrenom());
            $session->set('currentUserEmail', $chercheur->getEmail());
            $session->set('currentUserRole', $chercheur->getRole());
            
            return new RedirectResponse($this->generateUrl('app_homepage'));
        }
        else{
            return new RedirectResponse($this->generateUrl('auth_manager/index.html.twig'));

        }
    }

    
}