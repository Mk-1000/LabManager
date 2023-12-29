<?php

namespace App\Controller;

use App\Entity\Equipment;
use App\Form\EquipmentType;
use App\Repository\EquipmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/equipment')]
class EquipmentController extends AbstractController
{
    #[Route('/', name: 'app_equipment_index', methods: ['GET'])]
    public function index(Request $request, EquipmentRepository $EquipmentRepository): Response
{
    $searchTerm = $request->query->get('search');
    $equipment = [];

    if ($searchTerm) {
        $equipment = $EquipmentRepository->findByTitre($searchTerm);
    } else {
        $equipment = $EquipmentRepository->findAll();
    }

    return $this->render('equipment/index.html.twig', [
        'equipment' => $equipment,
    ]);
}

    #[Route('/new', name: 'app_equipment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        // gets an attribute by name
        $currentUserId = $session->get('currentUserId');

        $equipment = new Equipment();

        // Create the form and pass the current user ID as an option
        $form = $this->createForm(EquipmentType::class, $equipment, [
            'current_user_id' => $currentUserId,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($equipment);
            $entityManager->flush();

            return $this->redirectToRoute('app_equipment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('equipment/new.html.twig', [
            'equipment' => $equipment,
            'form' => $form->createView(), // Ensure you pass the view of the form to the template
        ]);
    }


    #[Route('/{id}', name: 'app_equipment_show', methods: ['GET'])]
    public function show(Equipment $equipment): Response
    {
        return $this->render('equipment/show.html.twig', [
            'equipment' => $equipment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_equipment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Equipment $equipment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EquipmentType::class, $equipment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_equipment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('equipment/edit.html.twig', [
            'equipment' => $equipment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_equipment_delete', methods: ['POST'])]
    public function delete(Request $request, Equipment $equipment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equipment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($equipment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_equipment_index', [], Response::HTTP_SEE_OTHER);
    }
}
