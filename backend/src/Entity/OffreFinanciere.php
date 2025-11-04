<?php

namespace App\Entity;

use App\Repository\OffreFinanciereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffreFinanciereRepository::class)]
class OffreFinanciere
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
    private string $totalHT = '0.00';

    /** @var Collection<int, Prix> */
    #[ORM\OneToMany(mappedBy: 'offre', targetEntity: Prix::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $prixLignes;

    public function __construct()
    {
        $this->prixLignes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefAO(): ?AppelOffre
    {
        return $this->refAO;
    }

    public function setRefAO(AppelOffre $refAO): static
    {
        $this->refAO = $refAO;
        return $this;
    }

    public function getRefPrestataire(): ?Prestataire
    {
        return $this->refPrestataire;
    }

    public function setRefPrestataire(Prestataire $refPrestataire): static
    {
        $this->refPrestataire = $refPrestataire;
        return $this;
    }

    public function getTotalHT(): string
    {
        return $this->totalHT;
    }

    public function setTotalHT(string $totalHT): static
    {
        $this->totalHT = $totalHT;
        return $this;
    }

    /** @return Collection<int, Prix> */
    public function getPrixLignes(): Collection
    {
        return $this->prixLignes;
    }

    public function addPrix(Prix $prix): static
    {
        if (!$this->prixLignes->contains($prix)) {
            $this->prixLignes->add($prix);
            $prix->setOffre($this);
        }
        return $this;
    }

    public function recalculateTotalFromLines(): void
    {
        $sum = 0.0;
        foreach ($this->prixLignes as $p) {
            $sum += (float)$p->getMontantHT();
        }
        $this->totalHT = number_format($sum, 2, '.', '');
    }
}


