<?php

namespace App\Repository;

use App\Entity\Specialist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Specialist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Specialist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Specialist[]    findAll()
 * @method Specialist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecialistRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Specialist::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof Specialist) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findMostAppointment($max){


        return $this->createQueryBuilder('u')
            ->andWhere('u.howManyAppointed = :max')
            ->setParameter('max', $max)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $username
     * @return Specialist|null
     * @throws NonUniqueResultException
     */
    public function findByEmail(string $username): ?Specialist
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param $id
     * @return Query Returns an array of Specialists objects
     */
    public function findById($id){
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

    }

    /**
     * @param Specialist $user
     * @throws ORMException
     */
    public function save(Specialist $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @param string|null $name
     * @param int|null $specialty
     * @return QueryBuilder
     */
    public function getWithSearchQueryBuilder(
        string $name = null,
        int $specialty = null
    ): QueryBuilder {
        $query = $this->createQueryBuilder('u')
            ->select('u, spec')
            ->join('u.doctorSpecialties', 'spec')
            ->where('u.firstName like :name');


        if (!is_null($specialty)) {
            $query->andWhere('spec.fk_specialty = :specId');
            $query->setParameters(['name' => '%' . $name . '%',  'specId' => $specialty]);
        } else {
            $query->setParameters(['name' => '%' . $name . '%', ]);
        }

        return $query;
    }
    // /**
    //  * @return Specialist[] Returns an array of Specialist objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Specialist
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
