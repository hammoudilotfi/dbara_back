<?php

namespace App\Repository;

use App\Entity\DbartiElPrefere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DbartiElPrefere>
 *
 * @method DbartiElPrefere|null find($id, $lockMode = null, $lockVersion = null)
 * @method DbartiElPrefere|null findOneBy(array $criteria, array $orderBy = null)
 * @method DbartiElPrefere[]    findAll()
 * @method DbartiElPrefere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DbartiElPrefereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DbartiElPrefere::class);
    }

    public function save(DbartiElPrefere $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DbartiElPrefere $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return DbartiElPrefere[] Returns an array of DbartiElPrefere objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DbartiElPrefere
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
