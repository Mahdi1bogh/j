<?php

namespace App\Repository;

use App\Entity\Lignecommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lignecommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lignecommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lignecommande[]    findAll()
 * @method Lignecommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lignecommande::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Lignecommande $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Lignecommande $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Lignecommande[] Returns an array of Lignecommande objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Lignecommande
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findCommandeDetailsByOneUser(int $getIdUser, int $id)
    {
        $qb = $this->createQueryBuilder('l')
            ->innerJoin('l.idCommande', 'c')
            ->innerJoin('l.idProduit','p')
            ->where('c.idUser = :idUser')
            ->andWhere('c.idCommande = :idCommande')
            ->setParameter('idCommande', $id)
            ->setParameter('idUser', $getIdUser)
            ->addSelect('c')
            ->addSelect('p')
        ;
        return $qb->getQuery()->getResult();
    }

    public function findWithProduitByCommande($id){
        return $this->createQueryBuilder('l')
            ->where('l.idCommande = :id')
            ->setParameter('id', $id)
            ->innerJoin('l.idProduit', 'd')
            ->addSelect('d')
            ->getQuery()->getResult()
            ;
    }

}
