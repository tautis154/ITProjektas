<?php

namespace App\Repository;

use App\Entity\DoctorSpecialty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DoctorSpecialty|null find($id, $lockMode = null, $lockVersion = null)
 * @method DoctorSpecialty|null findOneBy(array $criteria, array $orderBy = null)
 * @method DoctorSpecialty[]    findAll()
 * @method DoctorSpecialty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctorSpecialtyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DoctorSpecialty::class);
    }

    // /**
    //  * @return DoctorSpecialty[] Returns an array of DoctorSpecialty objects
    //  */
    public function findByUserIdAndSpecialtyId($userId, $specialtyId)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.fk_specialist = :userId')
            ->andWhere('u.fk_specialty = :specialtyId')
            ->setParameter('specialtyId', $specialtyId)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    public function findAllById($userId){
        return $this->createQueryBuilder('u')
            ->andWhere('u.fk_specialist = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return DoctorSpecialty[] Returns an array of DoctorSpecialty objects
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
    public function findOneBySomeField($value): ?DoctorSpecialty
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
