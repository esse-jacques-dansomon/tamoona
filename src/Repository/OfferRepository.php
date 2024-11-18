<?php

namespace App\Repository;

use App\Entity\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offer[]    findAll()
 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    /**
     * @return Offer[] Returns an array of Offer objects
     */

    public function findByCategoryAndIsDisplay($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.category = :val' )
            ->setParameter('val', 'senegal')
            ->orderBy('o.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByIsDisplayAndCategory($value, $category){
        return $this->createQueryBuilder('o')
            ->andWhere("o.category = :val")
            ->setParameter('val', $category)
            ->andWhere('o.isDisplayed = :isDisplayed')
            ->setParameter('isDisplayed', $value)
            ->orderBy('o.id', 'ASC')
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Offer
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
