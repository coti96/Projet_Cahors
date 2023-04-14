<?php

namespace App\Repository;

use App\Entity\Commutateur;
use App\Model\SearchData\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commutateur>
 *
 * @method Commutateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commutateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commutateur[]    findAll()
 * @method Commutateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommutateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commutateur::class);
    }

    public function save(Commutateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Commutateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
         /**
        * @return Commutateur[] Returns an array of Commutateur objects
         */
        public function search($mots)
    {
        $query= $this->createQueryBuilder('a');
        if(!empty($mots)) {
        $query->where('MATCH_AGAINST (a.marque) AGAINST(:mots boolean)>0 OR MATCH_AGAINST (a.nom) AGAINST(:mots boolean)>0')
        ->setParameter('mots',$mots);
    }
    

    return $query->getQuery()->getResult();

}


//    /**
//     * @return Commutateur[] Returns an array of Commutateur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Commutateur
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
