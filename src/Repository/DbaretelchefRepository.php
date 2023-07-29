<?php

namespace App\Repository;

use App\Entity\Dbaretelchef;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dbaretelchef>
 *
 * @method Dbaretelchef|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dbaretelchef|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dbaretelchef[]    findAll()
 * @method Dbaretelchef[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DbaretelchefRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dbaretelchef::class);
    }

    public function save(Dbaretelchef $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Dbaretelchef $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function searchByName($nom){
        return $this->createQueryBuilder('p')
            ->andwhere('p.nom LIKE :nom')
            ->setParameter('nom', '%' . $nom . '%')
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return Dbaretelchef[] Returns an array of Dbaretelchef objects
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

//    public function findOneBySomeField($value): ?Dbaretelchef
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
