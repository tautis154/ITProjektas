<?php

namespace App\Repository;

use App\Entity\DoctorWorkTime;
use App\Entity\Specialist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DoctorWorkTime|null find($id, $lockMode = null, $lockVersion = null)
 * @method DoctorWorkTime|null findOneBy(array $criteria, array $orderBy = null)
 * @method DoctorWorkTime[]    findAll()
 * @method DoctorWorkTime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctorWorkTimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DoctorWorkTime::class);
    }

    public function getWorkHours(Specialist $user)
    {
        return $this->getEntityManager()->getRepository(DoctorWorkTime::class)
            ->findBy(['fk_specialist' => $user->getId()]);
    }

    // /**
    //  * @return DoctorWorkTime[] Returns an array of DoctorWorkTime objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DoctorWorkTime
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
