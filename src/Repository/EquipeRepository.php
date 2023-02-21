<?php


namespace App\Repository;

use App\Entity\Equipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Equipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Equipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Equipe[]    findAll()
 * @method Equipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipe::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Equipe $entity, bool $flush = true): void
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
    public function remove(Equipe $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findJoueursByEquipe() {
        return $this->createQueryBuilder('e')

//            ->innerJoin('e.idJ1', 'j1')
//            ->innerJoin('e.idJ2', 'j2')
//            ->innerJoin('e.idJ3', 'j3')
//            ->innerJoin('e.idJ4', 'j4')
//            ->innerJoin('e.idJ5', 'j5')
//            ->addSelect('j1')
//            ->addSelect('j2')
//            ->addSelect('j3')
//            ->addSelect('j4')
//            ->addSelect('j5')
            ->orderBy('e.nomEquipe', 'ASC');
//            return $qb->getQuery()->getResult();
    }

    public function findUserEquipe($user) {
        $qb = $this->createQueryBuilder('e')
            ->innerJoin('e.idJ1', 'j1')
            ->innerJoin('e.idJ2', 'j2')
            ->innerJoin('e.idJ3', 'j3')
            ->innerJoin('e.idJ4', 'j4')
            ->innerJoin('e.idJ5', 'j5')
            ->addSelect('j1')
            ->addSelect('j2')
            ->addSelect('j3')
            ->addSelect('j4')
            ->addSelect('j5')
            ->where('j5.idUser = :u or j4.idUser = :u or j3.idUser = :u or j2.idUser = :u or j1.idUser = :u ')
            ->setParameter('u',$user)
//            ->where('e.idJ5 = :u')
//            ->orWhere('e.idJ4 = :u')
        ;

//            ->orderBy('e.nomEquipe', 'ASC');
        return $qb->getQuery()
            ->getResult();
    }

}
