<?php

namespace App\Entity;

use App\Repository\AppelOffreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Entite;

#[ORM\Entity(repositoryClass: AppelOffreRepository::class)]
#[ORM\Table(name: 'appel_offre')]
class AppelOffre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'numero_ao', length: 255)]
    private ?string $numeroAO = null;

    #[ORM\Column(name: 'date_publication', type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datePublication = null;

    #[ORM\Column(length: 255)]
    private ?string $objet = null;

    // Relation vers Entite (nouveau) tout en gardant l'ancien champ string pour compatibilité
    #[ORM\ManyToOne(targetEntity: Entite::class, inversedBy: 'appelsOffres')]
    private ?Entite $entiteEntity = null;

    #[ORM\Column(length: 50)]
    private ?string $entite = null;

    #[ORM\Column(length: 50)]
    private ?string $responsable = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $designation = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $unite = null;

    #[ORM\Column(name: 'prix_ht', type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $prixHT = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $quantite = null;

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroAO(): ?string
    {
        return $this->numeroAO;
    }

    public function setNumeroAO(string $numeroAO): static
    {
        $this->numeroAO = $numeroAO;
        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTimeInterface $datePublication): static
    {
        $this->datePublication = $datePublication;
        return $this;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): static
    {
        $this->objet = $objet;
        return $this;
    }

    public function getEntite(): ?string
    {
        return $this->entite;
    }

    public function setEntite(string $entite): static
    {
        $this->entite = $entite;
        return $this;
    }

    public function getEntiteEntity(): ?Entite
    {
        return $this->entiteEntity;
    }

    public function setEntiteEntity(?Entite $entite): static
    {
        $this->entiteEntity = $entite;
        return $this;
    }

    public function getResponsable(): ?string
    {
        return $this->responsable;
    }

    public function setResponsable(string $responsable): static
    {
        $this->responsable = $responsable;
        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(?string $designation): static
    {
        $this->designation = $designation;
        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(?string $unite): static
    {
        $this->unite = $unite;
        return $this;
    }

    public function getPrixHT(): ?string
    {
        return $this->prixHT;
    }

    public function setPrixHT(?string $prixHT): static
    {
        $this->prixHT = $prixHT;
        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): static
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
