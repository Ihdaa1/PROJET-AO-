<?php

namespace App\Entity;

use App\Repository\PrixRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrixRepository::class)]
class Prix
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: OffreFinanciere::class, inversedBy: 'prixLignes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OffreFinanciere $offre = null;

    #[ORM\Column(length: 255)]
    private ?string $designation = null;

    #[ORM\ManyToOne(targetEntity: Unite::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Unite $unite = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2)]
    private string $prixUnitaire = '0.00';

    #[ORM\Column(type: Types::INTEGER)]
    private int $quantite = 0;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2)]
    private string $montantHT = '0.00';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOffre(): ?OffreFinanciere
    {
        return $this->offre;
    }

    public function setOffre(OffreFinanciere $offre): static
    {
        $this->offre = $offre;
        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): static
    {
        $this->designation = $designation;
        return $this;
    }

    public function getUnite(): ?Unite
    {
        return $this->unite;
    }

    public function setUnite(Unite $unite): static
    {
        $this->unite = $unite;
        return $this;
    }

    public function getPrixUnitaire(): string
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(string $prixUnitaire): static
    {
        $this->prixUnitaire = $prixUnitaire;
        $this->recalculateMontant();
        return $this;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;
        $this->recalculateMontant();
        return $this;
    }

    public function getMontantHT(): string
    {
        return $this->montantHT;
    }

    public function setMontantHT(string $montantHT): static
    {
        $this->montantHT = $montantHT;
        return $this;
    }

    private function recalculateMontant(): void
    {
        $this->montantHT = number_format((float)$this->prixUnitaire * (int)$this->quantite, 2, '.', '');
    }
}


