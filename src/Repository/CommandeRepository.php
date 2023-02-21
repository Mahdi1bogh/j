<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Commande $entity, bool $flush = true): void
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
    public function remove(Commande $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    //    jointure commandes et users
    public function findCommandeByUsers(){
        return $this->createQueryBuilder('c')
            ->join('c.idUser', 'u')
            ->addSelect('u')

        ;
//        return $qb->getQuery()->getResult();
    }

    public function findCommandeByOneUser(?int $getIdUser)
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.idUser = :id')
            ->setParameter('id', $getIdUser)

        ;
        return $qb->getQuery()->getResult();
    }

    /**
     * Returns number of "Commandes" per day
     * @return void
     */
    public function countByDate(){
         $query = $this->createQueryBuilder('a')
             ->select('SUBSTRING(a.created_at, 1, 10) as dateCommande, COUNT(a) as count')
             ->groupBy('a.dateCommande')
         ;
         return $query->getQuery()->getResult();

    }

    public function findSearch(SearchData $search): PaginationInterface
    {

        $query = $this
            ->createQueryBuilder('a')
            ->select('c', 'a')
            ->join('a.commandes', 'c');



        if (!empty($search->min)) {
            $query = $query
                ->andWhere('a.totalPoints >= :min')
                ->setParameter('min', $search->min);
        }

        if (!empty($search->max)) {
            $query = $query
                ->andWhere('a.totalPoints <= :max')
                ->setParameter('max', $search->max);
        }

        if (!empty($search->attente)) {
            $query = $query
                ->andWhere('p.promo = 1');
        }

        if (!empty($search->commandes)) {
            $query = $query
                ->andWhere('c.id IN (:commandes)')
                ->setParameter('commandes', $search->categories);
        }

        return $this->paginator->paginate(
            $query,
            $search->page,
            9
        );
    }

    public function findMaxCommandeByUser($id)
    {
        /** @var int $idComm */
        $idComm = $this->createQueryBuilder('c')
            ->where('c.etat = :etat')
            ->setParameter('etat', 'En cours')
            ->orderBy('c.idCommande', 'DESC')
            ->setMaxResults(1)
            ->select('c.idCommande')
            ->getQuery()->getOneOrNullResult();
//        dd($qb1);
        if($idComm){
            return $this->createQueryBuilder('c')
                ->where('c.idUser = :id')
                ->setParameter('id', $id)
                ->andWhere('c.idCommande = :idComm')
                ->setParameter('idComm', $idComm)
                ->getQuery()->getOneOrNullResult()
                ;
        }
        return null;

    }





}
