<?php

namespace App\Repository;

use App\Entity\DbaraLiveFront;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DbaraLiveFront>
 *
 * @method DbaraLiveFront|null find($id, $lockMode = null, $lockVersion = null)
 * @method DbaraLiveFront|null findOneBy(array $criteria, array $orderBy = null)
 * @method DbaraLiveFront[]    findAll()
 * @method DbaraLiveFront[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DbaraLiveFrontRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DbaraLiveFront::class);
    }

    public function save(DbaraLiveFront $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DbaraLiveFront $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return DbaraLiveFront[] Returns an array of DbaraLiveFront objects
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

//    public function findOneBySomeField($value): ?DbaraLiveFront
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
