<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Chercheur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
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
    
    #[Route('/login', name: 'app_login')]
    public function login(
        Request $request,
        SessionInterface $session,
        EntityManagerInterface $entityManager
    ): Response {
        $error = null;

        if ($request->isMethod('POST')) {
            $email = $request->request->get('_username');
            $password = $request->request->get('_password');

            // Check if chercheur sign-in is successful
            $chercheur = Chercheur::chercheurSignInCheck($email, $password, $entityManager);

            if ($chercheur === null) {
                // If chercheur not found, check if admin sign-in is successful
                $admin = $entityManager->getRepository(Admin::class)->findOneBy(['email' => $email]);

                if ($admin === null || !password_verify($password, $admin->getPassword())) {
                    // Authentication failed for both chercheur and admin
                    $error = new AuthenticationException('Invalid credentials.');
                } else {
                    // Authentication succeeded for admin, set session variables and redirect
                    $session->set('isAdmin', true);
                    $session->set('currentUserId', $admin->getId());
                    $session->set('currentUserEmail', $admin->getEmail());
                    $session->set('currentUserRole', 'ROLE_ADMIN');

                    // Redirect to the admin dashboard or any admin route after successful login
                    return new RedirectResponse($this->generateUrl('app_publication_index'));
                }
            } else {
                // Authentication succeeded for chercheur, set session variables and redirect
                $session->set('isAdmin', false);
                $session->set('currentUserId', $chercheur->getId());
                $session->set('currentUserNom', $chercheur->getNom());
                $session->set('currentUserPrenom', $chercheur->getPrenom());
                $session->set('currentUserEmail', $chercheur->getEmail());
                $session->set('currentUserRole', $chercheur->getRole());

                // Redirect to the app_publication_index route after successful login
                return new RedirectResponse($this->generateUrl('app_publication_index'));
            }
        }

        return $this->render('auth_manager/signIn.html.twig', [
            'error' => $error,
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
}
