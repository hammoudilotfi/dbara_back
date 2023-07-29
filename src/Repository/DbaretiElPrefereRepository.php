<?php

namespace App\Repository;

use App\Entity\DbaretiElPrefere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DbaretiElPrefere>
 *
 * @method DbaretiElPrefere|null find($id, $lockMode = null, $lockVersion = null)
 * @method DbaretiElPrefere|null findOneBy(array $criteria, array $orderBy = null)
 * @method DbaretiElPrefere[]    findAll()
 * @method DbaretiElPrefere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DbaretiElPrefereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DbaretiElPrefere::class);
    }

    public function save(DbaretiElPrefere $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DbaretiElPrefere $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return DbaretiElPrefere[] Returns an array of DbaretiElPrefere objects
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

//    public function findOneBySomeField($value): ?DbaretiElPrefere
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
