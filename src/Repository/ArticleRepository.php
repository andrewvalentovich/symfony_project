<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function getPublishedLatest()
    {
        return $this->latest()
            ->andWhere('a.publishedAt IS NOT NULL')
            ->orderBy('a.publishedAt', 'DESC')
            ->leftJoin('a.comments', 'c')
            ->addSelect('c')
            ->leftJoin('a.tags', 't')
            ->addSelect('t')
            ->leftJoin('a.author', 'u')
            ->addSelect('u')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllPublishedLastWeek()
    {
        return $this->published($this->latest())
            ->andWhere('a.publishedAt >= :week_ago')
            ->setParameter(':week_ago', new \DateTime('-1 week'))
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllPublishedByParams(\DateTime $dateFrom, \DateTime $dateTo)
    {
        return $this->published($this->latest())
            ->andWhere('a.publishedAt >= :dateFrom AND a.publishedAt <= :dateTo')
            ->setParameter(':dateFrom', $dateFrom)
            ->setParameter(':dateTo', $dateTo)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllCreatedByParams(\DateTime $dateFrom, \DateTime $dateTo)
    {
        return $this->latest()
            ->andWhere('a.createdAt >= :dateFrom AND a.createdAt <= :dateTo')
            ->setParameter(':dateFrom', $dateFrom)
            ->setParameter(':dateTo', $dateTo)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllWithSoftDelNoResult(string $search = null)
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.author', 'h')
            ->addSelect('h');

        if ($search != null) {
            $qb
                ->andWhere('a.title LIKE :search OR a.body LIKE :search OR h.firstName LIKE :search')
                ->setParameter('search', "%$search%")
            ;
        }

        return $qb
            ->orderBy('a.publishedAt', 'DESC')
            ;
    }

    private function getOrCreateQueryBuilder(QueryBuilder $qb = null): QueryBuilder
    {
        return $qb ?? $this->createQueryBuilder('a');
    }

    public function latest(QueryBuilder $qb = null)
    {
        return $this->getOrCreateQueryBuilder($qb)->orderBy('a.publishedAt', 'DESC');
    }

    public function published(QueryBuilder $qb = null)
    {
        return $this->getOrCreateQueryBuilder($qb)->andWhere('a.publishedAt IS NOT NULL');
    }


    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
