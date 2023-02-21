<?php


namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Reclamation $entity, bool $flush = true): void
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
    public function remove(Reclamation $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findReclamationByUser($id){
        $qb = $this->createQueryBuilder('r')
            ->where('r.idUser = :id')
            ->setParameter('id', $id)
//            ->join('c.idUser', 'u')
//            ->addSelect('u')

            ;
        return $qb->getQuery()->getResult();
    }

    public function findReclamationWithUser($id)
    {
        $qb = $this->createQueryBuilder('r')
            ->join('r.idUser', 'u')
            ->addSelect('u')
            ->where('r.idReclam = :id')
            ->setParameter('id', $id)
            ;
        return $qb->getQuery()->getOneOrNullResult();
    }

}
