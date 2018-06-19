<?php

namespace Victor\AdBundle\Repository;

/**
 * OfferRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OfferRepository extends \Doctrine\ORM\EntityRepository
{

    public function FindByPrice($min, $max)
    {
        $qb = $this->createQueryBuilder('o');

        $qb->where('o.price > :min')
                ->setParameter('min', $min)
            ->andWhere('o.price < :max')
                ->setParameter('max', $max)
            ->andWhere('o.sold = 0')
            ->orderBy('o.price', 'ASC')
            ;

        return $qb
            ->getQuery()
            ->getResult();
    }

}
