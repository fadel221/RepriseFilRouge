<?php

namespace App\Repository;

use App\Entity\ProfilDataPersister;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProfilDataPersister|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfilDataPersister|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfilDataPersister[]    findAll()
 * @method ProfilDataPersister[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfilDataPersisterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfilDataPersister::class);
    }

     /**
      * @return ProfilDataPersister[] Returns an array of ProfilDataPersister objects
   */
    
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    

    
    public function findOneBySomeField($value): ?ProfilDataPersister
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
}
