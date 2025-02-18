<?php

namespace App\Service\Transaction;

use App\Entity\Transaction;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class Transfert
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(
        User $sender,
        User $receiver,
        string $typeSender,
        string $typeReceiver,
        string $typeTransaction,
        float $montant
    ) : Transaction
    {
        //Start transaction
        $this->entityManager->getConnection()->beginTransaction();
        try{
            //Check if sender has enough money
            if($sender->getSolde() < $montant){
                throw new \Exception('Sender has not enough money');
            }

            //Update sender and receiver balance
            $sender->setSolde($sender->getSolde() - $montant);
            $receiver->setSolde($receiver->getSolde() + $montant);
            //Create Transaction
            $transaction = new Transaction();
            $transaction->setIdSdr($sender->getId());
            $transaction->setIdRcv($receiver->getId());
            $transaction->setTypeSdr($typeSender);
            $transaction->setTypeRcv($typeReceiver);
            $transaction->setType($typeTransaction);
            $transaction->setState('done');
            $transaction->setMontant($montant);

            //Persist
            $this->entityManager->persist($transaction);
            $this->entityManager->persist($sender);
            $this->entityManager->persist($receiver);

            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            return $transaction;
        }catch (\Exception $e) {
            //Rollback transaction
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }
    }
}