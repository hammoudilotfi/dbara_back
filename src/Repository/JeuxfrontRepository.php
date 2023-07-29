<?php

namespace App\Repository;

use App\Entity\Jeuxfront;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Jeuxfront>
 *
 * @method Jeuxfront|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jeuxfront|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jeuxfront[]    findAll()
 * @method Jeuxfront[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JeuxfrontRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jeuxfront::class);
    }

    public function save(Jeuxfront $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Jeuxfront $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Jeuxfront[] Returns an array of Jeuxfront objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Jeuxfront
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
