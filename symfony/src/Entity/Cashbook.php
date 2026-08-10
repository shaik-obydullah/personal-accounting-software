<?php

namespace App\Entity;

use App\Repository\CashbookRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CashbookRepository::class)]
#[ORM\Table(name: 'cashbook')]
#[ORM\HasLifecycleCallbacks]
class Cashbook
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $inAmount = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $outAmount = null;

    #[ORM\Column(type: 'bigint', name: 'fk_reference_id')]
    private ?int $referenceId = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'bigint', nullable: true)]
    private ?int $createdBy = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: 'bigint', nullable: true)]
    private ?int $updatedBy = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeInterface $deletedAt = null;

    public function getId(): ?int { return $this->id; }

    public function getInAmount(): ?string { return $this->inAmount; }
    public function setInAmount(?string $inAmount): static { $this->inAmount = $inAmount; return $this; }

    public function getOutAmount(): ?string { return $this->outAmount; }
    public function setOutAmount(?string $outAmount): static { $this->outAmount = $outAmount; return $this; }

    public function getReferenceId(): ?int { return $this->referenceId; }
    public function setReferenceId(int $referenceId): static { $this->referenceId = $referenceId; return $this; }

    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; }
    public function setCreatedAt(?\DateTimeInterface $createdAt): static { $this->createdAt = $createdAt; return $this; }

    public function getCreatedBy(): ?int { return $this->createdBy; }
    public function setCreatedBy(?int $createdBy): static { $this->createdBy = $createdBy; return $this; }

    public function getUpdatedAt(): ?\DateTimeInterface { return $this->updatedAt; }
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static { $this->updatedAt = $updatedAt; return $this; }

    public function getUpdatedBy(): ?int { return $this->updatedBy; }
    public function setUpdatedBy(?int $updatedBy): static { $this->updatedBy = $updatedBy; return $this; }

    public function getDeletedAt(): ?\DateTimeInterface { return $this->deletedAt; }
    public function setDeletedAt(?\DateTimeInterface $deletedAt): static { $this->deletedAt = $deletedAt; return $this; }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
