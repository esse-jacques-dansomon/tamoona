<?php

namespace App\Repository;

use App\Entity\FeaturedOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FeaturedOffer>
 *
 * @method FeaturedOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeaturedOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeaturedOffer[]    findAll()
 * @method FeaturedOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeaturedOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeaturedOffer::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(FeaturedOffer $entity, bool $flush = true): void
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
    public function remove(FeaturedOffer $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return FeaturedOffer[] Returns an array of FeaturedOffer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FeaturedOffer
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByIsActive($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.isActive = :val')
            ->setParameter('val', $value)
            ->orderBy('f.number', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
