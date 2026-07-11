<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
#[ORM\Table(name: 'activities')]
#[ORM\HasLifecycleCallbacks]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Admin::class)]
    #[ORM\JoinColumn(name: 'fk_admin_id', referencedColumnName: 'id', nullable: true)]
    private ?Admin $admin = null;

    #[ORM\Column(length: 10)]
    private ?string $type = null;

    #[ORM\Column(length: 150)]
    private ?string $name = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $ipAddress = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $visitorCountry = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $visitorState = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $visitorCity = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $visitorAddress = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'bigint', nullable: true)]
    private ?int $createdBy = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeInterface $deletedAt = null;

    public function getId(): ?int { return $this->id; }

    public function getAdmin(): ?Admin { return $this->admin; }
    public function setAdmin(?Admin $admin): static { $this->admin = $admin; return $this; }

    public function getType(): ?string { return $this->type; }
    public function setType(string $type): static { $this->type = $type; return $this; }

    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }

    public function getIpAddress(): ?string { return $this->ipAddress; }
    public function setIpAddress(?string $ipAddress): static { $this->ipAddress = $ipAddress; return $this; }

    public function getVisitorCountry(): ?string { return $this->visitorCountry; }
    public function setVisitorCountry(?string $visitorCountry): static { $this->visitorCountry = $visitorCountry; return $this; }

    public function getVisitorState(): ?string { return $this->visitorState; }
    public function setVisitorState(?string $visitorState): static { $this->visitorState = $visitorState; return $this; }

    public function getVisitorCity(): ?string { return $this->visitorCity; }
    public function setVisitorCity(?string $visitorCity): static { $this->visitorCity = $visitorCity; return $this; }

    public function getVisitorAddress(): ?string { return $this->visitorAddress; }
    public function setVisitorAddress(?string $visitorAddress): static { $this->visitorAddress = $visitorAddress; return $this; }

    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; }
    public function setCreatedAt(?\DateTimeInterface $createdAt): static { $this->createdAt = $createdAt; return $this; }

    public function getCreatedBy(): ?int { return $this->createdBy; }
    public function setCreatedBy(?int $createdBy): static { $this->createdBy = $createdBy; return $this; }

    public function getDeletedAt(): ?\DateTimeInterface { return $this->deletedAt; }
    public function setDeletedAt(?\DateTimeInterface $deletedAt): static { $this->deletedAt = $deletedAt; return $this; }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }
}
