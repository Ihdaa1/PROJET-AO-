<?php

namespace App\Entity;

use App\Repository\MarcheRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MarcheRepository::class)]
class Marche
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: AppelOffre::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?AppelOffre $refAO = null;

    #[ORM\ManyToOne(targetEntity: Prestataire::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Prestataire $refPrestataire = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2)]
    private string $montantHT = '0.00';

    #[ORM\Column(length: 100)]
    private ?string $numero = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateSignature = null;

    public function getId(): ?int { return $this->id; }
    public function getRefAO(): ?AppelOffre { return $this->refAO; }
    public function setRefAO(AppelOffre $refAO): static { $this->refAO = $refAO; return $this; }
    public function getRefPrestataire(): ?Prestataire { return $this->refPrestataire; }
    public function setRefPrestataire(Prestataire $p): static { $this->refPrestataire = $p; return $this; }
    public function getMontantHT(): string { return $this->montantHT; }
    public function setMontantHT(string $m): static { $this->montantHT = $m; return $this; }
    public function getNumero(): ?string { return $this->numero; }
    public function setNumero(string $n): static { $this->numero = $n; return $this; }
    public function getDateSignature(): ?\DateTimeInterface { return $this->dateSignature; }
    public function setDateSignature(\DateTimeInterface $d): static { $this->dateSignature = $d; return $this; }
}


