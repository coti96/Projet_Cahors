<?php

namespace App\Repository;

use App\Entity\PareFeu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PareFeu>
 *
 * @method PareFeu|null find($id, $lockMode = null, $lockVersion = null)
 * @method PareFeu|null findOneBy(array $criteria, array $orderBy = null)
 * @method PareFeu[]    findAll()
 * @method PareFeu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PareFeuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PareFeu::class);
    }

    public function save(PareFeu $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PareFeu $entity, bool $flush = false): void
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
        $query->where('MATCH_AGAINST (a.nom) AGAINST (:mots boolean)>0  
        OR MATCH_AGAINST (a.editeur) AGAINST(:mots boolean)>0 
        OR MATCH_AGAINST (a.ip) AGAINST(:mots boolean)>0')
        ->setParameter('mots',$mots);
    }
    

//    /**
//     * @return PareFeu[] Returns an array of PareFeu objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PareFeu
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
}