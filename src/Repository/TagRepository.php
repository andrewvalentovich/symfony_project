<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function findAllWithSoftDel(string $search = null, string $showDeletedTags)
    {
        $qb = $this->createQueryBuilder('t');

        if ($search != null) {
            $qb
                ->andWhere('t.name LIKE :search OR t.slug LIKE :search')
                ->setParameter('search', "%$search%")
            ;
        }

        if ($showDeletedTags) {
            $this->getEntityManager()->getFilters()->disable('softdeleteable');
        }

        return $qb
            ->orderBy('t.createdAt', 'DESC')
            ->leftJoin('t.articles', 'a')
            ->addSelect('a')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllWithSoftDelNoResult(string $search = null, string $showDeleted)
    {
        $qb = $this->createQueryBuilder('t');

        if ($search != null) {
            $qb
                ->andWhere('t.name LIKE :search OR t.slug LIKE :search')
                ->setParameter('search', "%$search%")
            ;
        }

        if ($showDeleted) {
            $this->getEntityManager()->getFilters()->disable('softdeleteable');
        }

        return $qb
            ->orderBy('t.createdAt', 'DESC')
            ->leftJoin('t.articles', 'a')
            ->addSelect('a')
            ;
    }

    // /**
    //  * @return Tag[] Returns an array of Tag objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tag
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
