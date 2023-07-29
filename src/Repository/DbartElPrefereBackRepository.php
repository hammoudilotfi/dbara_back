<?php

namespace App\Repository;

use App\Entity\DbartElPrefereBack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DbartElPrefereBack>
 *
 * @method DbartElPrefereBack|null find($id, $lockMode = null, $lockVersion = null)
 * @method DbartElPrefereBack|null findOneBy(array $criteria, array $orderBy = null)
 * @method DbartElPrefereBack[]    findAll()
 * @method DbartElPrefereBack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DbartElPrefereBackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DbartElPrefereBack::class);
    }

    public function save(DbartElPrefereBack $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DbartElPrefereBack $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return DbartElPrefereBack[] Returns an array of DbartElPrefereBack objects
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

//    public function findOneBySomeField($value): ?DbartElPrefereBack
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
