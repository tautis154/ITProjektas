<?php

namespace App\Repository;

use App\Entity\Customer;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    /**
     * @param DateTime $date
     * @param int $specialistId
     * @return array|object[]
     */
    public function checkIfWorkHourExists(DateTime $date, int $specialistId)
    {
        return $this->getEntityManager()->getRepository(Customer::class)
            ->findBy([
                'fk_specialist' => $specialistId,
                'appointedTime' => $date,
            ]);
    }

    public function findByDate($id, $date){
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
        select  * 
        from customer
        where customer.fk_specialist_id = :id AND CAST(customer.appointed_time as DATE) = :date

        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id,
            'date'=>$date]);
        $stmt->execute();

        return $stmt->fetchAllAssociative();
    }

    public function findByGreaterDate($id, $date){
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
        select  * 
        from customer
        where customer.fk_specialist_id = :id AND customer.appointed_time  > :date
        ORDER BY customer.appointed_time ASC 
        LIMIT 1
        
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id,
            'date'=>$date]);
        $stmt->execute();

        return $stmt->fetchAllAssociative();
    }

    // /**
    //  * @return Customer[] Returns an array of Customer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Customer
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
