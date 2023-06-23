<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findAllSortedByName()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.firstName', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllActive()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.isActive IS NOT NULL')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllSubscribedUsers()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.emailWeeklyNewsletterSub = 1')
            ->andWhere('u.isActive = 1')
            ->getQuery()
            ->getResult()
            ;
    }

    /*
        public function selectUserById(int $userId)
        {
            return $this->createQueryBuilder('u')
                ->andWhere('u.isActive = :userId')
                ->setParameter('userId', $userId)
                ->getQuery()
                ->getRest()
            ;
        }


        /*
        public function findOneBySomeField($value): ?User
        {
            return $this->createQueryBuilder('u')
                ->andWhere('u.exampleField = :val')
                ->setParameter('val', $value)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }
        */
}
