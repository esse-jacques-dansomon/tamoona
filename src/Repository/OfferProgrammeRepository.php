<?php

namespace App\Repository;

use App\Entity\OfferProgramme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OfferProgramme>
 *
 * @method OfferProgramme|null find($id, $lockMode = null, $lockVersion = null)
 * @method OfferProgramme|null findOneBy(array $criteria, array $orderBy = null)
 * @method OfferProgramme[]    findAll()
 * @method OfferProgramme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferProgrammeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OfferProgramme::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(OfferProgramme $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(OfferProgramme $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return OfferProgramme[] Returns an array of OfferProgramme objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OfferProgramme
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
