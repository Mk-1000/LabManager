<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Admin;

use App\Entity\Chercheur;
use App\Entity\Equipment;
use App\Entity\Publication;
use App\Entity\ProjectDeRecherche;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
   private UserPasswordHasherInterface $passwordHasher;
   public function __construct(UserPasswordHasherInterface $passwordHasher)
   {
       $this->passwordHasher = $passwordHasher;
   }

   public function load(ObjectManager $manager)
   {

      $admin = new Admin();

      $admin->setEmail("admin@admin.com");
      $plaintextPassword= "admin";

      $hashedPassword = $this->passwordHasher->hashPassword(
         $admin,
         $plaintextPassword
      );

      $admin->setPassword($hashedPassword);

      $manager->persist($admin);


       $faker = Factory::create();
       $roles = ["Administrateur", "Superviseur", "Membre de l'équipe", "Gestionnaire de projet"];

       $chercheurs = [];

       for ($i = 1; $i < 6; $i++) {
           $chercheur = new Chercheur();
           $chercheur->setNom($faker->lastName);
           $chercheur->setPrenom($faker->firstName);
           $chercheur->setEmail("chercheur".$i."@gmail.com");
           $plaintextPassword= "chercheur";
            // // hash the password (based on the security.yaml config for the $user class)
            // $hashedPassword = $this->passwordHasher->hashPassword(
            //    $admin,
            //    $plaintextPassword
            // );
           $chercheur->setMotDePasse($plaintextPassword);

           $randomRole = $roles[array_rand($roles)];
           $chercheur->setRole($randomRole);
           
            $equipments = [];

            for ($k = 0; $k < 10; $k++) {
               $equipment = new Equipment();
               $equipment->setNom('Equipment ' . $i.$k);
               $equipment->setEtat(false);
               $equipment->setChercheur($chercheur);

               $equipments[] = $equipment;
               $manager->persist($equipment);
            }
            
            foreach ($equipments as $equipment) {
               $chercheur->addEquipment($equipment);
            }
            
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

               $numEquipments = random_int(0, 10);

               for ($k = 0; $k < min($numEquipments, count($equipments)); $k++) {
                  $equipment = $equipments[$k];
                  $verif = $equipment->useEquipment($projet);
              
                  if ($verif) {
                      $projet->addEquipment($equipment);
                  }
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
