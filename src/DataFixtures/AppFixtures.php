<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Chercheur;
use App\Entity\ProjectDeRecherche;
use App\Entity\Publication;
use App\Entity\Equipment;

use Faker\Factory;


class AppFixtures extends Fixture
{
   public function load(ObjectManager $manager)
   {

       $faker = Factory::create();
       $roles = ["Administrateur", "Superviseur", "Membre de l'équipe", "Gestionnaire de projet"];

       $chercheurs = [];

       for ($i = 0; $i < 5; $i++) {
           $chercheur = new Chercheur();
           $chercheur->setNom($faker->lastName);
           $chercheur->setPrenom($faker->firstName);
           $chercheur->setEmail($faker->email);
           $chercheur->setMotDePasse($faker->password);

           $randomRole = $roles[array_rand($roles)];
           $chercheur->setRole($randomRole);
           
           $projects = [];
           $numProjects = random_int(0, 5);

           for ($j = 0; $j < $numProjects; $j++) {
               $projet = new ProjectDeRecherche();
               $projet->setTitre($faker->word());
               $projet->setDescription($faker->words(10, true));
               $projet->setEtatAvancement(random_int(0, 100));
               $projet->setChercheur($chercheur);

               $publications = [];
               $numPublications = random_int(0, 4);

               for ($k = 0; $k < $numPublications; $k++) {
                  $publication = new Publication();
                  $publication->setTitre($faker->word());
                  $publication->setAuteurs($chercheur->getPrenom() . ' ' . $chercheur->getNom());
                  $publication->setMotsCles($faker->words(3, true));
                  $publication->setProjectDeRecherche($projet);

                  $publications[] = $publication;
                  $manager->persist($publication);
               }

               foreach ($publications as $publication) {
                  $projet->addPublication($publication);
               }

               $equipments = [];
               $numEquipments = random_int(0, 10);

               for ($k = 0; $k < $numEquipments; $k++) {
                  $equipment = new Equipment();
                  $equipment->setNom('Equipment ' . $i.$k);
                  $equipment->setEtat(true);
                  $equipment->setProjectDeRecherche($projet);

                  $equipments[] = $equipment;
                  $manager->persist($equipment);
               }

               foreach ($equipments as $equipment) {
                  $projet->addEquipment($equipment);
               }

               $projects[] = $projet;
               $manager->persist($projet);
           }

           foreach ($projects as $project) {
               $chercheur->addProject($project);
           }

           $chercheurs[] = $chercheur;
           $manager->persist($chercheur);
       }
       

       $manager->flush();
   }
}
