<?php

namespace App\Repository;

use App\Entity\Tontine;
use App\Entity\Transaction;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 *
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function add(Transaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Transaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    //Get Tontine related transactions sender
    public function getTontinesTransactionsSdr(Tontine $tontine): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.idRcv = :tontine')
            ->andWhere('t.typeRcv = :typeRcv')
            ->setParameter('tontine', $tontine->getId())
            ->setParameter('typeRcv', 'tontine')
            ->getQuery()
            ->getResult();
    }

    //Get Tontine related transactions receiver
    public function getTontinesTransactionsRcv(Tontine $tontine): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.idSdr = :tontine')
            ->andWhere('t.typeSdr = :typeSdr')
            ->setParameter('tontine', $tontine->getId())
            ->setParameter('typeSdr', 'tontine')
            ->getQuery()
            ->getResult();
    }

    //Get User related transactions
    public function getUsersTransactions(User $user)
    {
        //Find all transactions where typeRcv or typeSdr is user and sender or receiver is equal to user id
        return $this->createQueryBuilder('t')
            ->where('t.typeRcv = :user')
            ->orWhere('t.typeSdr = :user')
            
            ->andWhere('t.idRcv = :userId')
            ->orWhere('t.idSdr = :userId')
            ->setParameter('user', 'user')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Transaction[] Returns an array of Transaction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Transaction
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
