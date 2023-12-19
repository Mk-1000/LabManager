<?php

namespace App\Repository;

use App\Entity\ProjectDeRecherche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectDeRecherche>
 *
 * @method ProjectDeRecherche|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectDeRecherche|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectDeRecherche[]    findAll()
 * @method ProjectDeRecherche[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectDeRechercheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectDeRecherche::class);
    }

//    /**
//     * @return ProjectDeRecherche[] Returns an array of ProjectDeRecherche objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProjectDeRecherche
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
