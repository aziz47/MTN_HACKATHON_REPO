<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Carbon\CarbonImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 't_transactions')]
#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idSdr = null;

    #[ORM\Column]
    private ?int $idRcv = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $typeRcv = null;

    #[ORM\Column(length: 255)]
    private ?string $typeSdr = null;

    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdSdr(): ?int
    {
        return $this->idSdr;
    }

    public function setIdSdr(int $idSdr): self
    {
        $this->idSdr = $idSdr;

        return $this;
    }

    public function getIdRcv(): ?int
    {
        return $this->idRcv;
    }

    public function setIdRcv(int $idRcv): self
    {
        $this->idRcv = $idRcv;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): self
    {
        $this->createdAt = CarbonImmutable::now();

        return $this;
    }

    public function getTypeRcv(): ?string
    {
        return $this->typeRcv;
    }

    public function setTypeRcv(string $typeRcv): self
    {
        $this->typeRcv = $typeRcv;

        return $this;
    }

    public function getTypeSdr(): ?string
    {
        return $this->typeSdr;
    }

    public function setTypeSdr(string $typeSdr): self
    {
        $this->typeSdr = $typeSdr;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function ifTontine(): bool
    {
        return $this->typeRcv === 'tontine' || $this->typeSdr === 'tontine';
    }

    public function whoIsTontine(): string
    {
        return $this->typeRcv === 'tontine' ? 'receiver' : 'sender';
    }

    public function getTontineId(): int
    {
        return $this->whoIsTontine() === 'receiver' ? $this->idRcv : $this->idSdr;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'idSdr' => $this->idSdr,
            'idRcv' => $this->idRcv,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'typeRcv' => $this->typeRcv,
            'typeSdr' => $this->typeSdr,
            'state' => $this->state,
            'montant' => $this->montant,
            'type' => $this->type,
        ];
    }
}
