<?php

namespace App\Repository;

use App\Entity\Produits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produits[]    findAll()
 * @method Produits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produits::class);
    }
    /**
     * @param $cat
     * @return Produits[]
     */
    public function findProduitsByCategory($cat): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.idCategorie = :c')
            ->setParameter('c', $cat)
        ;
        return $qb->getQuery()->getResult();
    }



}