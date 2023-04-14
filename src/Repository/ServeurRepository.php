<?php

namespace App\Repository;

use App\Entity\Serveur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Model\SearchData\SearchData;

/**
 * @extends ServiceEntityRepository<Serveur>
 *
 * @method Serveur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serveur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serveur[]    findAll()
 * @method Serveur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServeurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serveur::class);
    }

    public function save(Serveur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Serveur $entity, bool $flush = false): void
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
        $query->where('MATCH_AGAINST(a. Nom) AGAINST (:mots boolean)>0 
        OR MATCH_AGAINST (a. Marque) AGAINST(:mots boolean)>0  
        OR MATCH_AGAINST (a.ip) AGAINST (:mots boolean)>0')
        ->setParameter('mots',$mots);
    }
    

    return $query->getQuery()->getResult();

}

    

}
//    /**
//     * @return Serveur[] Returns an array of Serveur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Serveur
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
