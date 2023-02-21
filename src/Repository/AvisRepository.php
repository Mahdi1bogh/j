<?php


namespace App\Repository;

use App\Entity\Avis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Avis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Avis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Avis[]    findAll()
 * @method Avis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avis::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Avis $entity, bool $flush = true): void
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
    public function remove(Avis $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findAvisByUser($id){
        $qb = $this->createQueryBuilder('r')
            ->where('r.idUser = :id')
            ->setParameter('id', $id)
//            ->join('c.idUser', 'u')
//            ->addSelect('u')

            ;
        return $qb->getQuery()->getResult();
    }

    public function findAvisWithUser($id)
    {
        $qb = $this->createQueryBuilder('r')
            ->join('r.idUser', 'u')
            ->addSelect('u')
            ->where('r.idAvis = :id')
            ->setParameter('id', $id)
            ;
        return $qb->getQuery()->getOneOrNullResult();
    }

}
