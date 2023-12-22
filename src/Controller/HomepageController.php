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
    public function index( ): Response
    {
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }
}

