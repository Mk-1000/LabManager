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
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

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

    #[Route('/sign_in', name: 'app_signIn', methods: ['GET'])]
    public function signIn(): Response
    {
        return $this->render('auth_manager/signIn.html.twig', [
        ]);
    }

    #[Route('/sign_up', name: 'app_signUp', methods: ['GET'])]
    public function signUp(): Response
    {
        return $this->render('auth_manager/signUp.html.twig', [
        ]);
        
    }
    

    #[Route('/login', name: 'app_login')]

    public function login(Request $request,SessionInterface $session,EntityManagerInterface $entityManager): RedirectResponse
    {
        // Clear the user's session
        // Use the injected $session object to set the attribute
        $session->set('currentUserId', 2);

        $chercheur = Chercheur::findChercheurById($entityManager, 2);
        if ($chercheur) {
            $session->set('currentUserNom', $chercheur->getNom());
            $session->set('currentUserPrenom', $chercheur->getPrenom());
            $session->set('currentUserEmail', $chercheur->getEmail());
            $session->set('currentUserRole', $chercheur->getRole());
            
            return new RedirectResponse($this->generateUrl('app_publication_index'));
        }
        else{
            return new RedirectResponse($this->generateUrl('app_homepage'));

        }
    }

    // private AuthorizationCheckerInterface $authChecker;

    // public function __construct(AuthorizationCheckerInterface $authChecker)
    // {
    //     $this->authChecker = $authChecker;
    // }

    // // Your sign-in action where the user is authenticated
    // public function signInAction()
    // {
    //     // Check if the user is logged in and is an admin
    //     if ($this->authChecker->isGranted('ROLE_ADMIN')) {
    //         // User is an admin, handle accordingly
    //         // For example, redirect to the admin dashboard
    //         return $this->redirectToRoute('admin_dashboard');
    //     } else {
    //         // User is not an admin, handle accordingly
    //         // For example, redirect to a regular user dashboard or show an error
    //         return $this->redirectToRoute('regular_user_dashboard');
    //     }
    // }
    
    
}
