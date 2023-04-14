<?php

namespace App\Repository;

use App\Entity\Routeur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Routeur>
 *
 * @method Routeur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Routeur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Routeur[]    findAll()
 * @method Routeur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RouteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Routeur::class);
    }

    public function save(Routeur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Routeur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function search ($mots)
    {
        $query= $this->createQueryBuilder('a');
        if(!empty($mots)) {
        $query->where('MATCH_AGAINST (a. Nom) AGAINST (:mots boolean)>0 
        OR MATCH_AGAINST (a.ip) AGAINST (:mots boolean)>0')
        ->setParameter('mots',$mots);
    }
    

    return $query->getQuery()->getResult();

}

//    /**
//     * @return Routeur[] Returns an array of Routeur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Routeur
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
