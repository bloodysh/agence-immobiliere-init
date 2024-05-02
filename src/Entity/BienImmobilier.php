<?php

namespace App\Entity;

use App\Repository\BienImmobilierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BienImmobilierRepository::class)]
class BienImmobilier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $rue = null;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    private ?string $codePostal = null;

    #[ORM\ManyToOne(inversedBy: 'lesBien')]
    private ?User $leUser = null;

    /**
     * @var Collection<int, Annonce>
     */
    #[ORM\OneToMany(mappedBy: 'bienImmobilier', targetEntity: Annonce::class)]
    private Collection $annonceRelation;

    /**
     * @var Collection<int, Piece>
     */
    #[ORM\OneToMany(mappedBy: 'bienImmobilier', targetEntity: Piece::class)]
    private Collection $piece;

    public function __construct()
    {
        $this->annonceRelation = new ArrayCollection();
        $this->piece = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): static
    {
        $this->rue = $rue;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getLeUser(): ?User
    {
        return $this->leUser;
    }

    public function setLeUser(?User $leUser): static
    {
        $this->leUser = $leUser;

        return $this;
    }

    /**
     * @return Collection<int, Annonce>
     */
    public function getAnnonceRelation(): Collection
    {
        return $this->annonceRelation;
    }

    public function addAnnonceRelation(Annonce $annonceRelation): static
    {
        if (!$this->annonceRelation->contains($annonceRelation)) {
            $this->annonceRelation->add($annonceRelation);
            $annonceRelation->setBienImmobilier($this);
        }

        return $this;
    }

    public function removeAnnonceRelation(Annonce $annonceRelation): static
    {
        if ($this->annonceRelation->removeElement($annonceRelation)) {
            // set the owning side to null (unless already changed)
            if ($annonceRelation->getBienImmobilier() === $this) {
                $annonceRelation->setBienImmobilier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Piece>
     */
    public function getPiece(): Collection
    {
        return $this->piece;
    }

    public function addPiece(Piece $piece): static
    {
        if (!$this->piece->contains($piece)) {
            $this->piece->add($piece);
            $piece->setBienImmobilier($this);
        }

        return $this;
    }

    public function removePiece(Piece $piece): static
    {
        if ($this->piece->removeElement($piece)) {
            // set the owning side to null (unless already changed)
            if ($piece->getBienImmobilier() === $this) {
                $piece->setBienImmobilier(null);
            }
        }

        return $this;
    }

    public function surfaceHabitable(): float
    {
        $surfaceHabitable = 0;

        foreach ($this->piece as $piece) {
            if ($piece->getTypePiece()->isSurfaceHabitable()) {
                $surfaceHabitable += $piece->getSurface();
            }
        }

        return $surfaceHabitable;
    }

    public function surfaceNonHabitable(): float
    {
        $surfaceNonHabitable = 0;

        foreach ($this->piece as $piece) {
            if (!$piece->getTypePiece()->isSurfaceHabitable()) {
                $surfaceNonHabitable += $piece->getSurface();
            }
        }

        return $surfaceNonHabitable;
    }
}
